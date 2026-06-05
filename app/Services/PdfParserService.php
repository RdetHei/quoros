<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class PdfParserService
{
    public function parse($filePath)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();

        // Standardize newlines
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Regex to find chapters: 
        // 1. Lines starting with Chapter, Chp, Eps, Episode, Bab (case insensitive)
        // 2. Lines that are likely titles (short, capitalized, followed by many lines) - harder in PDF
        $pattern = '/(?:\n|^)(?=(?:chapter|chp|eps|episode|bab|bagian|part)\s*\d+)/i';
        
        $parts = preg_split($pattern, $text, -1, PREG_SPLIT_NO_EMPTY);
        
        $chapters = [];
        foreach ($parts as $index => $content) {
            $content = trim($content);
            if (empty($content)) continue;

            $lines = explode("\n", $content);
            $title = trim($lines[0]);
            
            // Basic heuristic: if the first line is very long, it's probably not a title
            if (strlen($title) > 120) {
                $title = "Chapter " . (count($chapters) + 1);
                $body = $content;
            } else {
                array_shift($lines);
                $body = implode("\n", $lines);
            }

            $chapters[] = [
                'title' => $title,
                'content' => trim($body)
            ];
        }

        // If no chapters were detected via keywords, return the whole thing as one chapter
        if (empty($chapters)) {
            $chapters[] = [
                'title' => 'Full Story',
                'content' => trim($text)
            ];
        }

        return $chapters;
    }
}
