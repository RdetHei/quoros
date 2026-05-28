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
        $currentChapter = [
            'title' => '',
            'content' => []
        ];

        // Keywords that indicate a new chapter
        $chapterKeywords = ['chapter', 'chp', 'eps', 'episode', 'bab', 'bagian', 'part'];

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text = $this->getElementText($element);
                $trimmedText = trim($text);

                if (empty($trimmedText)) continue;

                // Check if this element is a heading or starts with chapter keywords
                $isHeading = false;
                if (method_exists($element, 'getFontStyle')) {
                    $style = $element->getParagraphStyle();
                    if ($style && (str_contains($style, 'Heading') || str_contains($style, 'Title'))) {
                        $isHeading = true;
                    }
                }

                $isChapterMatch = false;
                foreach ($chapterKeywords as $keyword) {
                    if (stripos($trimmedText, $keyword) === 0) {
                        $isChapterMatch = true;
                        break;
                    }
                }

                if ($isHeading || $isChapterMatch) {
                    // Save previous chapter if exists
                    if (!empty($currentChapter['content'])) {
                        $chapters[] = [
                            'title' => $currentChapter['title'] ?: 'Chapter ' . (count($chapters) + 1),
                            'content' => implode("<br>", $currentChapter['content'])
                        ];
                    }
                    
                    $currentChapter = [
                        'title' => $trimmedText,
                        'content' => []
                    ];
                } else {
                    $currentChapter['content'][] = e($trimmedText);
                }
            }
        }

        // Add last chapter
        if (!empty($currentChapter['content'])) {
            $chapters[] = [
                'title' => $currentChapter['title'] ?: 'Chapter ' . (count($chapters) + 1),
                'content' => implode("<br>", $currentChapter['content'])
            ];
        }

        return $chapters;
    }

    private function getElementText($element)
    {
        $text = '';
        if ($element instanceof Text) {
            $text .= $element->getText();
        } elseif ($element instanceof TextRun) {
            foreach ($element->getElements() as $childElement) {
                $text .= $this->getElementText($childElement);
            }
        } elseif (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $childElement) {
                $text .= $this->getElementText($childElement);
            }
        }
        return $text;
    }
}
