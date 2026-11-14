<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;

class DocxParserService
{
    public function parse($filePath)
    {
        $phpWord = IOFactory::load($filePath);
        $chapters = [];
        $metadata = [
            'title' => null,
            'description' => null,
            'genres' => null,
            'tags' => null,
        ];
        
        $currentChapter = [
            'title' => '',
            'content' => []
        ];

        $hasHeading1 = false;
        $fullText = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text = $this->getElementText($element);
                $trimmedText = trim($text);
                $fullText .= $text . "\n";

                if (empty($trimmedText)) {
                    if (!empty($currentChapter['title'])) {
                        $currentChapter['content'][] = '';
                    }
                    continue;
                }

                // 1. Metadata Block Detection (Prioritized before first chapter)
                if (empty($chapters) && empty($currentChapter['title'])) {
                    $cleanText = ltrim($trimmedText);
                    if (preg_match('/^Title:\s*(.*)$/i', $cleanText, $matches)) {
                        $metadata['title'] = trim($matches[1]);
                        continue;
                    }
                    if (preg_match('/^Description:\s*(.*)$/i', $cleanText, $matches)) {
                        $metadata['description'] = trim($matches[1]);
                        continue;
                    }
                    if (preg_match('/^Genres:\s*(.*)$/i', $cleanText, $matches)) {
                        $metadata['genres'] = trim($matches[1]);
                        continue;
                    }
                    if (preg_match('/^Tags:\s*(.*)$/i', $cleanText, $matches)) {
                        $metadata['tags'] = trim($matches[1]);
                        continue;
                    }
                }

                // 2. Chapter Delimiter: Heading 1
                $isHeading1 = false;
                
                // Handle Title elements (Heading 1, 2, etc.)
                if ($element instanceof \PhpOffice\PhpWord\Element\Title) {
                    $depth = $element->getDepth();
                    if ($depth == 1) {
                        $isHeading1 = true;
                        $hasHeading1 = true;
                    }
                } 
                // Handle Paragraph elements with styles
                if (!$isHeading1 && method_exists($element, 'getParagraphStyle')) {
                    $style = $element->getParagraphStyle();
                    
                    // If style is an object, get its name
                    if (is_object($style) && method_exists($style, 'getStyleName')) {
                        $style = $style->getStyleName();
                    } elseif (is_object($style)) {
                        $style = method_exists($style, '__toString') ? (string)$style : null;
                    }

                    // Handle integer style (Heading level)
                    if (is_numeric($style) && (int)$style === 1) {
                        $isHeading1 = true;
                        $hasHeading1 = true;
                    }

                    // Hanya Heading level 1 yang memisahkan chapter. Heading 2/3
                    // biasanya sub-judul di dalam satu chapter, bukan chapter baru.
                    if (!$isHeading1 && $style && is_string($style)
                        && preg_match('/(?:heading|titre|berschrift|titulo)[\s\-_]*1(?:\b|$)/i', $style)) {
                        $isHeading1 = true;
                        $hasHeading1 = true;
                    }
                }
                
                if ($isHeading1) {
                    // Save previous chapter if it has a title
                    if (!empty($currentChapter['title'])) {
                        $chapters[] = [
                            'title' => $currentChapter['title'],
                            'content' => implode("\n", $currentChapter['content'])
                        ];
                    }
                    
                    $currentChapter = [
                        'title' => $trimmedText,
                        'content' => []
                    ];
                } else {
                    // 3. Content: Text with Normal style (or any non-heading)
                    // Only collect content if we are already inside a chapter
                    if (!empty($currentChapter['title'])) {
                        // Preserve empty lines for paragraph spacing
                        $currentChapter['content'][] = $trimmedText;
                    }
                }
            }
        }

        // Add last chapter
        if (!empty($currentChapter['title'])) {
            $chapters[] = [
                'title' => $currentChapter['title'],
                'content' => implode("\n", $currentChapter['content'])
            ];
        }

        // 4. Validation & Fallback
        // Heading 1 tidak selalu dipakai penulis. Jika tidak ada Heading 1, atau
        // hanya ada satu bab (mis. Heading 1 cuma untuk judul buku), coba pecah
        // ulang berdasarkan penanda teks "Chapter / Bab / Part" dan pakai hasil
        // yang menghasilkan pemisahan lebih banyak.
        if (! empty($fullText)) {
            if (! $hasHeading1 || count($chapters) < 2) {
                $keywordChapters = NovelParserService::splitByKeywords($fullText);

                if (count($keywordChapters) > count($chapters)) {
                    return $keywordChapters;
                }
            }
        }

        if (empty($chapters) && ! empty($fullText)) {
            return NovelParserService::splitByKeywords($fullText);
        }

        return $chapters;
    }

    private function getElementText($element)
    {
        if ($element instanceof \PhpOffice\PhpWord\Element\TextBreak) {
            return "\n";
        }

        if (method_exists($element, 'getText')) {
            $text = $element->getText();
            if (is_string($text)) return $this->normalizeText($text);
        }

        $text = '';
        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $childElement) {
                $text .= $this->getElementText($childElement);
            }
        }
        return $this->normalizeText($text);
    }

    private function normalizeText(string $text): string
    {
        // Handle special characters like curly quotes, various dashes and ellipses
        $search = [
            "\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9a", "\xe2\x80\x9b", // single quotes
            "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x9e", "\xe2\x80\x9f", // double quotes
            "\xe2\x80\x93", "\xe2\x80\x94", // dashes
            "\xe2\x80\xa6", // ellipsis
            "‘", "’", "‚", "‛",
            "“", "”", "„", "‟",
            "–", "—", "…",
        ];
        $replace = [
            "'", "'", "'", "'",
            '"', '"', '"', '"',
            '-', '--',
            '...',
            "'", "'", "'", "'",
            '"', '"', '"', '"',
            '-', '--', '...',
        ];
        $text = str_replace($search, $replace, $text);

        // Decode HTML entities if any
        $decoded = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Normalize whitespace
        $decoded = str_replace(["\r\n", "\r"], "\n", $decoded);
        $decoded = preg_replace("/[ \t]+/u", ' ', $decoded);
        // Normalize multiple newlines to double newlines (paragraphed)
        $decoded = preg_replace("/\n{3,}/u", "\n\n", $decoded);
        
        return trim($decoded ?? '');
    }
}
