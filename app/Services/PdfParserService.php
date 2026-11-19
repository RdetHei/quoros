<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class PdfParserService
{
    public function __construct(private DocumentSegmenter $segmenter) {}

    public function parse(string $filePath): array
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
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

        return $this->segmenter->segment($blocks);
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
        $blocks = [];
        $paragraph = [];
        $lineIndexes = [];
        $count = count($lines);

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

        foreach ($lines as $lineIndex => $line) {
            $line = trim(preg_replace('/\s+/u', ' ', $line) ?? $line);

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
                ];
                continue;
            }

            $paragraph[] = $line;
            $lineIndexes[] = $lineIndex;
        }

        $flush();

        return $blocks;
    }
}
