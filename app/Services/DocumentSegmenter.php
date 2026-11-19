<?php

namespace App\Services;

class DocumentSegmenter
{
    public function isExplicitHeading(string $text): bool
    {
        $candidate = $this->candidate(['text' => $text]);

        return $candidate !== null
            && in_array($candidate['method'], ['explicit_number', 'semantic_heading'], true);
    }

    /** @param list<array<string, mixed>> $blocks */
    public function segment(array $blocks): array
    {
        $blocks = $this->normalizeBlocks($blocks);
        $blocks = $this->removeTableOfContentsPages($blocks);

        if ($blocks === []) {
            return [];
        }

        $candidates = [];
        $weakCandidates = [];

        foreach ($blocks as $index => $block) {
            $candidate = $this->candidate($block);
            if (! $candidate) {
                continue;
            }

            if ($candidate['confidence'] >= 0.75) {
                $candidates[$index] = $candidate;
            } else {
                $weakCandidates[$index] = $candidate;
            }
        }

        if ($candidates === [] && count($weakCandidates) >= 2) {
            $candidates = $weakCandidates;
        }

        if ($candidates === []) {
            return [$this->makeSegment($blocks, 0, count($blocks), '', 'unsegmented', 0.25, 'unknown', [
                'Struktur bab tidak terdeteksi dengan yakin. Dokumen dipertahankan utuh; tinjau dan beri judul sebelum menyimpan.',
            ])];
        }

        $segments = [];
        $starts = array_keys($candidates);
        $intro = array_slice($blocks, 0, $starts[0]);

        foreach ($starts as $candidateIndex => $start) {
            $end = $starts[$candidateIndex + 1] ?? count($blocks);
            $candidate = $candidates[$start];
            $contentBlocks = array_slice($blocks, $start + 1, $end - $start - 1);

            if ($candidateIndex === 0 && $intro !== []) {
                $contentBlocks = array_merge($intro, $contentBlocks);
            }

            $warnings = [];
            if ($candidateIndex === 0 && $intro !== []) {
                $warnings[] = 'Teks pembuka sebelum heading pertama digabungkan ke bagian ini agar tidak hilang.';
            }
            if ($candidate['confidence'] < 0.75) {
                $warnings[] = 'Heading dikenali dari struktur yang lemah; pastikan batas bagian ini benar.';
            }

            $segments[] = $this->makeSegment(
                $contentBlocks,
                $start,
                $end,
                $candidate['title'],
                $candidate['method'],
                $candidate['confidence'],
                $candidate['type'],
                $warnings,
                $blocks[$start]['source_page'] ?? null,
            );
        }

        return $segments;
    }

    /** @param list<array<string, mixed>> $blocks
     *  @return list<array<string, mixed>>
     */
    private function normalizeBlocks(array $blocks): array
    {
        $clean = [];
        $edgeCounts = [];

        foreach ($blocks as $block) {
            $text = trim((string) ($block['text'] ?? ''));
            $text = str_replace(["\r\n", "\r"], "\n", $text);
            $text = preg_replace('/[\t ]+/u', ' ', $text) ?? $text;
            $text = preg_replace('/ *\n */u', "\n", $text) ?? $text;
            $text = trim($text);

            if ($text === '' || preg_match('/^\d{1,4}$/u', $text)) {
                continue;
            }

            $block['text'] = $text;
            $clean[] = $block;

            if (! empty($block['page_edge']) && mb_strlen($text) <= 80) {
                $key = mb_strtolower($text);
                $edgeCounts[$key] = ($edgeCounts[$key] ?? 0) + 1;
            }
        }

        return array_values(array_filter($clean, function (array $block) use ($edgeCounts) {
            if (empty($block['page_edge']) || mb_strlen($block['text']) > 80) {
                return true;
            }

            return ($edgeCounts[mb_strtolower($block['text'])] ?? 0) < 2;
        }));
    }

    /** @param list<array<string, mixed>> $blocks
     *  @return list<array<string, mixed>>
     */
    private function removeTableOfContentsPages(array $blocks): array
    {
        $pages = [];
        foreach ($blocks as $block) {
            if (! isset($block['source_page'])) {
                continue;
            }

            $pages[(int) $block['source_page']][] = $block['text'];
        }

        if ($pages === []) {
            return $blocks;
        }

        $tocPages = [];
        $markerSeen = false;
        $previousTocPage = null;

        foreach ($pages as $page => $texts) {
            $joined = implode("\n", $texts);
            $hasMarker = preg_match('/\b(table of contents|contents|daftar isi|daftar kandungan)\b/iu', $joined) === 1;
            $entryCount = 0;
            foreach ($texts as $text) {
                if (preg_match('/\.{2,}\s*\d{1,4}\s*$/u', $text)
                    || (preg_match('/\s+\d{1,3}\s*$/u', $text) && $this->candidate(['text' => preg_replace('/\s+\d{1,3}\s*$/u', '', $text) ?? $text]))) {
                    $entryCount++;
                }
            }

            $looksLikeEntries = $entryCount >= 3 && $entryCount >= count($texts) * 0.35;
            if ($hasMarker || $looksLikeEntries || ($markerSeen && $previousTocPage !== null && $page <= $previousTocPage + 1 && $entryCount >= 2)) {
                $tocPages[$page] = true;
                $markerSeen = $markerSeen || $hasMarker;
                $previousTocPage = $page;
            } elseif ($previousTocPage !== null && $page > $previousTocPage + 1) {
                $markerSeen = false;
            }
        }

        if ($tocPages === []) {
            return $blocks;
        }

        return array_values(array_filter($blocks, fn (array $block) => ! isset($tocPages[(int) ($block['source_page'] ?? 0)])));
    }

    /** @param array<string, mixed> $block
     *  @return array{title: string, method: string, confidence: float, type: string}|null
     */
    private function candidate(array $block): ?array
    {
        $title = trim((string) ($block['text'] ?? ''));
        if ($title === '' || mb_strlen($title) > 160 || preg_match('/^\[\s*selesai\s*\]$/iu', $title)) {
            return null;
        }

        if (preg_match('/^(?:chapter|chap(?:ter)?\.?|ch\.?|bab|bagian|part|episode|eps?\.?|volume|book)\s+(?:#?\d+|[ivxlcdm]+|one|two|three|four|five|six|seven|eight|nine|ten)\b/iu', $title)) {
            return ['title' => $title, 'method' => 'explicit_number', 'confidence' => 0.96, 'type' => 'main'];
        }

        if (preg_match('/^(?:prologue|prolog|epilogue|epilog|intermission|interlude|afterword|selingan|pendahuluan|kata pengantar|kekalahan\s+(?:pertama|kedua|ketiga|keempat|kelima|keenam|ketujuh|kedelapan|kesembilan|kesepuluh|\d+))(?:\s*[:.\-—]|\s*$)/iu', $title)) {
            return [
                'title' => $title,
                'method' => 'semantic_heading',
                'confidence' => 0.94,
                'type' => $this->chapterType($title),
            ];
        }

        if (! empty($block['nav_boundary']) && mb_strlen($title) <= 120) {
            return ['title' => $title, 'method' => 'epub_navigation', 'confidence' => 0.9, 'type' => $this->chapterType($title)];
        }

        $level = (int) ($block['heading_level'] ?? 0);
        if ($level === 1 && mb_strlen($title) <= 120 && ! preg_match('/[.!?]$/u', $title)) {
            return ['title' => $title, 'method' => 'document_heading_1', 'confidence' => 0.82, 'type' => 'main'];
        }

        if (! empty($block['isolated_heading']) && mb_strlen($title) <= 90 && ! preg_match('/[.!?]$/u', $title)) {
            return ['title' => $title, 'method' => 'isolated_heading', 'confidence' => 0.62, 'type' => 'main'];
        }

        return null;
    }

    private function chapterType(string $title): string
    {
        return match (true) {
            preg_match('/\b(prologue|prolog|pendahuluan)\b/iu', $title) === 1 => 'prologue',
            preg_match('/\b(intermission|interlude|selingan)\b/iu', $title) === 1 => 'intermission',
            preg_match('/\b(epilogue|epilog)\b/iu', $title) === 1 => 'epilogue',
            preg_match('/\b(afterword|kata pengantar)\b/iu', $title) === 1 => 'afterword',
            default => 'main',
        };
    }

    /** @param list<array<string, mixed>> $blocks
     *  @param list<string> $warnings
     *  @return array<string, mixed>
     */
    private function makeSegment(array $blocks, int $start, int $end, string $title, string $method, float $confidence, string $type, array $warnings, ?int $headingPage = null): array
    {
        $pages = array_values(array_filter(array_map(
            fn (array $block) => isset($block['source_page']) ? (int) $block['source_page'] : null,
            $blocks
        )));

        return [
            'title' => $title,
            'content' => implode("\n\n", array_column($blocks, 'text')),
            'original_position' => $start,
            'source_page_start' => $pages === [] ? $headingPage : min(array_merge($pages, array_filter([$headingPage]))),
            'source_page_end' => $pages === [] ? null : end($pages),
            'heading_page' => $headingPage,
            'detection_method' => $method,
            'confidence' => $confidence,
            'chapter_type' => $type,
            'warnings' => $warnings,
        ];
    }
}