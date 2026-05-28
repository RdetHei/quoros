<?php

namespace App\Services;

use ZipArchive;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Str;

class EpubParserService
{
    public function parse($filePath)
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception("Gagal membuka file EPUB.");
        }

        // 1. Cari file container.xml untuk menemukan path ke file .opf
        $containerContent = $zip->getFromName('META-INF/container.xml');
        if (!$containerContent) {
            throw new \Exception("Format EPUB tidak valid (META-INF/container.xml tidak ditemukan).");
        }

        $containerDom = new DOMDocument();
        $containerDom->loadXML($containerContent);
        $opfPath = $containerDom->getElementsByTagName('rootfile')->item(0)->getAttribute('full-path');
        $baseDir = dirname($opfPath);
        if ($baseDir === '.') $baseDir = '';
        else $baseDir .= '/';

        // 2. Baca file .opf untuk mendapatkan daftar item (manifest) dan urutan bab (spine)
        $opfContent = $zip->getFromName($opfPath);
        $opfDom = new DOMDocument();
        $opfDom->loadXML($opfContent);
        $xpath = new DOMXPath($opfDom);
        $xpath->registerNamespace('opf', 'http://www.idpf.org/2007/opf');

        $manifest = [];
        $items = $opfDom->getElementsByTagName('item');
        foreach ($items as $item) {
            $manifest[$item->getAttribute('id')] = [
                'href' => $item->getAttribute('href'),
                'media-type' => $item->getAttribute('media-type')
            ];
        }

        $spine = [];
        $itemrefs = $opfDom->getElementsByTagName('itemref');
        foreach ($itemrefs as $itemref) {
            $idref = $itemref->getAttribute('idref');
            if (isset($manifest[$idref])) {
                $spine[] = $manifest[$idref]['href'];
            }
        }

        // 3. Ekstrak konten dari setiap file di spine
        $allChapters = [];
        foreach ($spine as $index => $href) {
            $fullPath = $baseDir . $href;
            $content = $zip->getFromName($fullPath);
            if (!$content) continue;

            $chapterData = $this->cleanChapterContent($content);
            
            // Jika konten kosong setelah dibersihkan, lewati
            if (empty(trim(strip_tags($chapterData['content'])))) {
                continue;
            }

            // Cek apakah di dalam satu file ini ada banyak bab (berdasarkan h1/h2 atau keyword)
            $subChapters = $this->splitInternalChapters($chapterData);
            foreach ($subChapters as $sub) {
                $allChapters[] = $sub;
            }
        }

        $zip->close();
        return $allChapters;
    }

    private function splitInternalChapters($chapterData)
    {
        $content = $chapterData['content'];
        $title = $chapterData['title'];

        // Pattern untuk mendeteksi pemisah bab di dalam HTML (h1, h2, atau teks tebal/besar yang mengandung keyword)
        $pattern = '/<(h[1-2])>(.*?)<\/\1>|(?:\n|^|<p>)\s*(?:chapter|chp|eps|episode|bab|bagian|part)\s*\d+/i';
        
        if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            // Jika ditemukan penanda bab, pecah berdasarkan itu
            if (count($matches[0]) > 0) {
                $parts = preg_split($pattern, $content);
                $chapters = [];
                
                foreach ($matches[0] as $index => $match) {
                    $matchText = $match[0];
                    // Ambil judul dari match (bersihkan tag jika itu h1/h2)
                    $cTitle = trim(strip_tags($matchText));
                    $cContent = $parts[$index + 1] ?? '';
                    
                    if (!empty(trim(strip_tags($cContent)))) {
                        $chapters[] = [
                            'title' => $cTitle,
                            'content' => trim($cContent)
                        ];
                    }
                }

                // Jika berhasil memecah, kembalikan. Jika tidak, fallback ke satu bab.
                if (!empty($chapters)) return $chapters;
            }
        }

        // Fallback: satu file = satu bab
        return [[
            'title' => $title ?: "Chapter",
            'content' => $content
        ]];
    }

    private function cleanChapterContent($html)
    {
        $dom = new DOMDocument();
        // Sembunyikan error karena HTML di EPUB mungkin tidak valid sempurna
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Ambil judul dari tag h1, h2, atau title
        $title = '';
        $h1 = $dom->getElementsByTagName('h1')->item(0);
        $h2 = $dom->getElementsByTagName('h2')->item(0);
        $titleTag = $dom->getElementsByTagName('title')->item(0);

        if ($h1) $title = $h1->textContent;
        elseif ($h2) $title = $h2->textContent;
        elseif ($titleTag) $title = $titleTag->textContent;

        // Bersihkan body
        $body = $dom->getElementsByTagName('body')->item(0);
        if ($body) {
            // Hapus elemen yang tidak perlu dibaca.
            $xpath = new DOMXPath($dom);
            foreach ($xpath->query('//script|//style|//noscript') as $node) {
                $node->parentNode?->removeChild($node);
            }

            $content = $this->extractReadableText($body);
        } else {
            $content = $this->normalizeText(strip_tags(html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        }

        return [
            'title' => trim($title),
            'content' => trim($content)
        ];
    }

    private function extractReadableText(\DOMNode $body): string
    {
        $blockTags = ['p', 'div', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'pre'];
        $lines = [];

        foreach ($blockTags as $tag) {
            foreach ($body->getElementsByTagName($tag) as $node) {
                $text = $this->normalizeText($node->textContent ?? '');
                if ($text !== '') {
                    $lines[] = $text;
                }
            }
        }

        if (!empty($lines)) {
            return implode("\n\n", $lines);
        }

        return $this->normalizeText($body->textContent ?? '');
    }

    private function normalizeText(string $text): string
    {
        $decoded = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $decoded = str_replace(["\r\n", "\r"], "\n", $decoded);
        $decoded = preg_replace("/[ \t]+/u", ' ', $decoded);
        $decoded = preg_replace("/\n{3,}/u", "\n\n", $decoded);

        return trim($decoded ?? '');
    }
}
