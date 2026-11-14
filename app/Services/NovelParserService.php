<?php

namespace App\Services;

class NovelParserService
{
    protected $epubParser;
    protected $docxParser;
    protected $pdfParser;

    public function __construct(
        EpubParserService $epubParser,
        DocxParserService $docxParser,
        PdfParserService $pdfParser
    ) {
        $this->epubParser = $epubParser;
        $this->docxParser = $docxParser;
        $this->pdfParser = $pdfParser;
    }

    public function parse($filePath, $extension)
    {
        return match (strtolower($extension)) {
            'epub' => $this->epubParser->parse($filePath),
            'docx' => $this->docxParser->parse($filePath),
            'pdf' => $this->pdfParser->parse($filePath),
            default => throw new \Exception("Format file tidak didukung: {$extension}"),
        };
    }

    /**
     * Pecah satu blok teks panjang menjadi chapter berdasarkan penanda judul.
     *
     * Dijalankan dua tahap supaya dialog huruf kapital tidak salah dianggap
     * judul: (1) penanda eksplisit "Chapter 1 / Bab 2 / Part 3", lalu (2) baris
     * judul HURUF BESAR bila tahap pertama tidak menemukan penanda apa pun.
     */
    public static function splitByKeywords(string $text): array
    {
        $text = trim(str_replace(["\r\n", "\r"], "\n", $text));

        if ($text === '') {
            return [];
        }

        $chapters = self::splitByHeadingLines($text, false);
        if (! empty($chapters)) {
            return $chapters;
        }

        $chapters = self::splitByHeadingLines($text, true);
        if (! empty($chapters)) {
            return $chapters;
        }

        return [['title' => 'Full Story', 'content' => $text]];
    }

    /**
     * @param  bool  $allowCapsHeadings  Izinkan baris HURUF BESAR dianggap judul.
     */
    private static function splitByHeadingLines(string $text, bool $allowCapsHeadings): array
    {
        $chapters = [];
        $title = null;
        $buffer = [];

        foreach (explode("\n", $text) as $line) {
            $trimmed = trim($line);

            if ($trimmed !== '' && self::isChapterHeading($trimmed, $allowCapsHeadings)) {
                if ($title !== null) {
                    $chapters[] = [
                        'title' => $title,
                        'content' => trim(implode("\n", $buffer)),
                    ];
                }

                $title = $trimmed;
                $buffer = [];
                continue;
            }

            // Buang kata pengantar (judul buku, metadata) sebelum judul chapter pertama.
            if ($title === null) {
                continue;
            }

            $buffer[] = $line;
        }

        if ($title !== null) {
            $chapters[] = [
                'title' => $title,
                'content' => trim(implode("\n", $buffer)),
            ];
        }

        return $chapters;
    }

    private static function isChapterHeading(string $line, bool $allowCapsHeadings): bool
    {
        if (mb_strlen($line) > 120) {
            return false;
        }

        // "Chapter 12", "Ch. 3: The Awakening", "Bab 4", "Part 5", "Prologue".
        if (preg_match('/^(?:chapter|chp|chap|bab|bagian|part|episode|eps|volume|book|prologue|epilogue|interlude|afterword)\b/i', $line)) {
            return true;
        }

        if (! $allowCapsHeadings || mb_strlen($line) > 80) {
            return false;
        }

        // Baris judul HURUF BESAR, minimal tiga huruf kapital, tanpa diakhiri
        // tanda baca kalimat agar kalimat kapital seperti "STOP!" tidak lolos.
        return (bool) preg_match('/^[A-Z0-9][A-Z0-9 \'\-:,\.!&\/]+$/', $line)
            && (bool) preg_match('/[A-Z]{3,}/', $line)
            && ! preg_match('/[.!?,;:]$/', $line);
    }
}
