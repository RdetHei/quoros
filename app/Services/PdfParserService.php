<?php

namespace App\Services;

use App\Support\ParagraphReflow;
use Smalot\PdfParser\Parser;
use Symfony\Component\Process\Process;

class PdfParserService
{
    public function __construct(private DocumentSegmenter $segmenter) {}

    public function parse(string $filePath, ?string $password = null): array
    {
        $parser = new Parser();

        try {
            $pdf = $parser->parseFile($filePath);
        } catch (\Throwable $e) {
            // Library smalot/pdfparser tidak mendukung PDF berpassword/enkripsi.
            if (! preg_match('/secured pdf|possible secured/i', $e->getMessage())) {
                throw $e;
            }

            $decryptedPath = $this->decryptProtectedPdf($filePath, $password);

            if ($decryptedPath === null) {
                throw new \RuntimeException($this->protectedPdfMessage($password), 0, $e);
            }

            try {
                $pdf = $parser->parseFile($decryptedPath);
            } finally {
                @unlink($decryptedPath);
            }
        }

        $pages = $pdf->getPages();
        $pageTexts = array_map(fn ($page) => trim($page->getText()), $pages);
        $narrativeStart = $this->findNarrativeStart($pageTexts);
        $blocks = [];

        foreach ($pageTexts as $pageIndex => $pageText) {
            if ($pageText === '' || ($narrativeStart !== null && $pageIndex < $narrativeStart && ! $this->looksLikeNarrative($pageText))) {
                continue;
            }

            array_push($blocks, ...$this->pageBlocks($pageText, $pageIndex + 1));
        }

        if ($blocks === []) {
            throw new \RuntimeException('PDF tidak memiliki lapisan teks yang dapat dibaca. Jalankan OCR pada dokumen hasil scan, lalu unggah ulang.');
        }

        return $this->segmenter->segment($this->mergeContinuations($blocks));
    }

    /**
     * Coba membuka proteksi PDF memakai qpdf menjadi berkas sementara yang bersih.
     *
     * Mengembalikan path berkas hasil dekripsi, atau null bila qpdf tidak ada
     * atau PDF gagal dibuka (password salah / butuh password).
     */
    private function decryptProtectedPdf(string $filePath, ?string $password): ?string
    {
        $binary = $this->qpdfBinary();

        if ($binary === null) {
            return null;
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'quoros_pdf_');

        if ($tempPath === false) {
            return null;
        }

        $command = [$binary, '--decrypt'];

        if (is_string($password) && $password !== '') {
            $command[] = '--password='.$password;
        }

        // "--" mengakhiri opsi agar path berkas tidak pernah ditafsirkan sebagai flag.
        $command[] = '--';
        $command[] = $filePath;
        $command[] = $tempPath;

        try {
            $process = new Process($command);
            $process->setTimeout(120);
            $process->run();
        } catch (\Throwable) {
            @unlink($tempPath);

            return null;
        }

        // qpdf memakai 0 = sukses, 3 = sukses dengan peringatan.
        if (! in_array($process->getExitCode(), [0, 3], true) || ! $this->isPdf($tempPath)) {
            @unlink($tempPath);

            return null;
        }

        return $tempPath;
    }

    /**
     * Pesan kegagalan dekripsi yang sesuai konteks (alat belum tersedia,
     * password salah, atau PDF meminta password).
     */
    private function protectedPdfMessage(?string $password): string
    {
        if ($this->qpdfBinary() === null) {
            return 'PDF ini terproteksi dan server belum memiliki alat untuk membukanya. Buka dokumen di pembaca PDF lalu simpan ulang tanpa proteksi, kemudian unggah ulang.';
        }

        if (is_string($password) && $password !== '') {
            return 'PDF ini terproteksi dan password yang dimasukkan tidak cocok. Periksa kembali password-nya, atau hapus proteksi lalu unggah ulang.';
        }

        return 'PDF ini terproteksi dan meminta password. Isi kolom "Password PDF" lalu unggah ulang — atau hapus proteksinya lebih dulu.';
    }

    /**
     * Deteksi path binary qpdf (di-cache agar hanya dijalankan sekali).
     */
    private function qpdfBinary(): ?string
    {
        static $resolved = false;
        static $binary = null;

        if ($resolved) {
            return $binary;
        }

        $resolved = true;

        $candidate = (string) config('services.qpdf.binary', 'qpdf');

        try {
            $process = new Process([$candidate, '--version']);
            $process->setTimeout(10);
            $process->run();

            if ($process->isSuccessful()) {
                $binary = $candidate;
            }
        } catch (\Throwable) {
            $binary = null;
        }

        return $binary;
    }

    private function isPdf(string $path): bool
    {
        if (! is_file($path) || filesize($path) < 5) {
            return false;
        }

        return str_starts_with((string) file_get_contents($path, false, null, 0, 5), '%PDF-');
    }

    /**
     * Sambungkan paragraf yang terpotong di batas halaman. Paragraf yang belum
     * ditutup tanda baca akhir kalimat di dasar halaman digabung dengan blok
     * pertama halaman berikutnya agar tidak terbelah menjadi dua paragraf.
     *
     * @param  list<array<string, mixed>>  $blocks
     * @return list<array<string, mixed>>
     */
    private function mergeContinuations(array $blocks): array
    {
        $merged = [];

        foreach ($blocks as $block) {
            $lastIndex = count($merged) - 1;
            $last = $lastIndex >= 0 ? $merged[$lastIndex] : null;

            $crossesPage = $last !== null
                && ($last['source_page'] ?? null) !== ($block['source_page'] ?? null);

            if ($crossesPage
                && empty($block['heading'])
                && empty($last['heading'])
                && ! $this->endsSentence((string) $last['text'])) {
                $merged[$lastIndex]['text'] = trim($merged[$lastIndex]['text'].' '.$block['text']);
                $merged[$lastIndex]['page_edge'] = (bool) ($merged[$lastIndex]['page_edge'] ?? false)
                    || (bool) ($block['page_edge'] ?? false);
                continue;
            }

            $merged[] = $block;
        }

        return $merged;
    }

    private function endsSentence(string $text): bool
    {
        return (bool) preg_match('/[.!?…]["\'”’)\]]*\s*$/u', trim($text));
    }

    /** @param list<string> $pages */
    private function findNarrativeStart(array $pages): ?int
    {
        foreach ($pages as $index => $text) {
            if ($this->looksLikeNarrative($text)) {
                return $index;
            }
        }

        return null;
    }

    private function looksLikeNarrative(string $text): bool
    {
        if (preg_match('/\b(table of contents|contents|daftar isi|isbn|copyright|hak cipta|all rights reserved|penerbit|publisher)\b/iu', $text)) {
            return false;
        }

        $letters = preg_match_all('/[\p{L}]{2,}/u', $text);
        $sentences = preg_match_all('/[.!?]["”’\')\]]?\s/u', $text);

        return $letters >= 24 && mb_strlen($text) >= 160 && $sentences >= 2;
    }

    /** @return list<array<string, mixed>> */
    private function pageBlocks(string $text, int $page): array
    {
        $lines = preg_split('/\R/u', str_replace("\f", "\n", $text)) ?: [];
        $count = count($lines);

        $clean = [];
        foreach ($lines as $lineIndex => $line) {
            $clean[$lineIndex] = trim(preg_replace('/[\t ]+/u', ' ', (string) $line) ?? (string) $line);
        }

        $width = ParagraphReflow::columnWidth($clean);

        $blocks = [];
        $paragraph = [];
        $lineIndexes = [];

        $flush = function () use (&$paragraph, &$lineIndexes, &$blocks, $page, $count): void {
            if ($paragraph === []) {
                return;
            }

            $text = trim(implode(' ', $paragraph));
            if ($text !== '') {
                $firstLine = min($lineIndexes);
                $lastLine = max($lineIndexes);
                $blocks[] = [
                    'text' => $text,
                    'source_page' => $page,
                    'page_edge' => $firstLine < 2 || $lastLine >= $count - 2,
                    'isolated_heading' => count($paragraph) === 1
                        && mb_strlen($text) <= 90
                        && ! preg_match('/[.!?]$/u', $text),
                ];
            }

            $paragraph = [];
            $lineIndexes = [];
        };

        foreach ($clean as $lineIndex => $line) {
            if ($line === '' || preg_match('/^\d{1,4}$/u', $line)) {
                $flush();
                continue;
            }

            if ($this->segmenter->isExplicitHeading($line)) {
                $flush();
                $blocks[] = [
                    'text' => $line,
                    'source_page' => $page,
                    'page_edge' => $lineIndex < 2 || $lineIndex >= $count - 2,
                    'heading' => true,
                ];
                continue;
            }

            // Baris baru dipisah menjadi paragraf sendiri bila terdeteksi batas
            // paragraf; jika tidak, baris dilanjutkan ke paragraf yang sama.
            if ($paragraph !== []
                && ParagraphReflow::startsNewParagraph($paragraph[count($paragraph) - 1], $line, $width)) {
                $flush();
            }

            $paragraph[] = $line;
            $lineIndexes[] = $lineIndex;
        }

        $flush();

        return $blocks;
    }
}
