<?php

namespace App\Services;

use Illuminate\Support\Str;

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
     * Helper to split content by chapter keywords if it's all in one big block
     */
    public static function splitByKeywords(string $text): array
    {
        // Pattern: Matches "Chapter 1", "Chp 2", "Episode 3", "Bab 4", "Part 5", etc.
        // Also matches lines that look like titles (all caps or short lines before long text)
        $pattern = '/(?:\n|^)(?=(?:chapter|chp|eps|episode|bab|bagian|part)\s*\d+|(?:[A-Z\s]{5,}\n))/i';
        
        $parts = preg_split($pattern, $text, -1, PREG_SPLIT_NO_EMPTY);
        
        $chapters = [];
        foreach ($parts as $index => $content) {
            $lines = explode("\n", trim($content));
            $title = $lines[0];
            
            // If title is too long, it might not be a title
            if (strlen($title) > 100) {
                $title = "Chapter " . ($index + 1);
                $body = $content;
            } else {
                array_shift($lines);
                $body = implode("\n", $lines);
            }

            $chapters[] = [
                'title' => trim($title),
                'content' => nl2br(trim($body))
            ];
        }

        return $chapters;
    }
}
