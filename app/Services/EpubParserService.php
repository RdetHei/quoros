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

        // 1. Find .opf file via container.xml
        $containerContent = $zip->getFromName('META-INF/container.xml');
        if (!$containerContent) {
            throw new \Exception("Format EPUB tidak valid (META-INF/container.xml tidak ditemukan).");
        }

        $containerDom = new DOMDocument();
        if (!@$containerDom->loadXML($containerContent)) {
            // Silently fail or log if needed, but continue
        }
        $rootfile = $containerDom->getElementsByTagName('rootfile')->item(0);
        if (!$rootfile) {
            throw new \Exception("Format EPUB tidak valid (rootfile tidak ditemukan).");
        }
        $opfPath = $rootfile->getAttribute('full-path');
        $baseDir = dirname($opfPath);
        if ($baseDir === '.') $baseDir = '';
        else $baseDir .= '/';

        // 2. Parse .opf for Metadata, Manifest, and Spine
        $opfContent = $zip->getFromName($opfPath);
        if (!$opfContent) {
            throw new \Exception("OPF content not found at $opfPath");
        }
        $opfDom = new DOMDocument();
        if (!@$opfDom->loadXML($opfContent)) {
            throw new \Exception("Failed to load OPF XML");
        }
        $xpath = new DOMXPath($opfDom);
        $xpath->registerNamespace('opf', 'http://www.idpf.org/2007/opf');
        $xpath->registerNamespace('dc', 'http://purl.org/dc/elements/1.1/');

        // Extract Metadata (Dublin Core)
        $metadata = [
            'title' => $xpath->query('//dc:title')->item(0)?->nodeValue,
            'author' => $xpath->query('//dc:creator')->item(0)?->nodeValue,
            'description' => $xpath->query('//dc:description')->item(0)?->nodeValue,
            'language' => $xpath->query('//dc:language')->item(0)?->nodeValue,
        ];

        // Parse Manifest
        $manifest = [];
        $items = $opfDom->getElementsByTagName('item');
        $tocId = null;
        $coverId = null;

        foreach ($items as $item) {
            $id = $item->getAttribute('id');
            $href = $item->getAttribute('href');
            $mediaType = $item->getAttribute('media-type');
            $properties = $item->getAttribute('properties');

            $manifest[$id] = [
                'href' => $href,
                'media-type' => $mediaType
            ];

            // Identify TOC (NCX for v2, Nav for v3)
            if ($mediaType === 'application/x-dtbncx+xml') {
                $tocId = $id;
            } elseif (str_contains($properties, 'nav')) {
                $tocId = $id;
            }

            // Identify Cover
            if (str_contains($properties, 'cover-image') || $id === 'cover' || $id === 'cover-image') {
                $coverId = $id;
            }
        }

        // Parse Spine
        $spine = [];
        $itemrefs = $opfDom->getElementsByTagName('itemref');
        foreach ($itemrefs as $itemref) {
            $idref = $itemref->getAttribute('idref');
            if (isset($manifest[$idref])) {
                $spine[] = $manifest[$idref]['href'];
            }
        }

        // 3. Parse TOC for Chapter Titles
        $chapterTitles = [];
        if ($tocId && isset($manifest[$tocId])) {
            $tocPath = $baseDir . $manifest[$tocId]['href'];
            $tocContent = $zip->getFromName($tocPath);
            if ($tocContent) {
                $chapterTitles = $this->parseToc($tocContent, $manifest[$tocId]['media-type']);
            }
        }

        // 4. Extract and Clean Chapters
        $allChapters = [];
        foreach ($spine as $index => $href) {
            $fullPath = $baseDir . $href;
            
            // Optimization: Use stream if content is large (for now getFromName is fine for typical xhtml)
            $content = $zip->getFromName($fullPath);
            if (!$content) {
                continue;
            }

            $cleaned = $this->cleanHtmlContent($content);
            $plainText = $this->extractReadableText($cleaned['node']);
            
            // Skip empty content
            if (empty(trim($plainText))) {
                continue;
            }

            // Title Detection Priority: 1. TOC, 2. H1/H2 from file, 3. Default
            $title = $chapterTitles[$href] ?? $cleaned['title'] ?? "Chapter " . (count($allChapters) + 1);

            $allChapters[] = [
                'title' => $title,
                'content' => $plainText,
                'metadata' => $index === 0 ? $metadata : null // Attach metadata only to first item for controller
            ];
        }

        $zip->close();
        return $allChapters;
    }

    private function parseToc($content, $mediaType)
    {
        $titles = [];
        $dom = new DOMDocument();
        @$dom->loadXML($content);
        $xpath = new DOMXPath($dom);

        if ($mediaType === 'application/x-dtbncx+xml') {
            // NCX (EPUB 2)
            $xpath->registerNamespace('ncx', 'http://www.daisy.org/z3986/2005/ncx/');
            $navPoints = $xpath->query('//ncx:navPoint');
            foreach ($navPoints as $point) {
                $label = $xpath->query('ncx:navLabel/ncx:text', $point)->item(0)?->nodeValue;
                $src = $xpath->query('ncx:content', $point)->item(0)?->getAttribute('src');
                if ($label && $src) {
                    $cleanSrc = explode('#', $src)[0];
                    $titles[$cleanSrc] = trim($label);
                }
            }
        } else {
            // Nav (EPUB 3)
            $xpath->registerNamespace('xhtml', 'http://www.w3.org/1999/xhtml');
            $xpath->registerNamespace('epub', 'http://www.idpf.org/2007/ops');
            $links = $xpath->query('//xhtml:nav[@epub:type="toc"]//xhtml:a');
            foreach ($links as $link) {
                $label = $link->nodeValue;
                $src = $link->getAttribute('href');
                if ($label && $src) {
                    $cleanSrc = explode('#', $src)[0];
                    $titles[$cleanSrc] = trim($label);
                }
            }
        }

        return $titles;
    }

    private function cleanHtmlContent($html)
    {
        $dom = new DOMDocument();
        // EPUB XHTML often has namespaces, we use loadHTML for easier cleaning
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        $xpath = new DOMXPath($dom);

        // 1. Remove scripts, styles, and other noise
        foreach ($xpath->query('//script|//style|//link|//meta|//iframe|//noscript') as $node) {
            $node->parentNode?->removeChild($node);
        }

        // 2. Extract Title from H1 or H2
        $title = null;
        $h1 = $dom->getElementsByTagName('h1')->item(0);
        $h2 = $dom->getElementsByTagName('h2')->item(0);
        if ($h1) $title = trim($h1->textContent);
        elseif ($h2) $title = trim($h2->textContent);

        // 3. Clean attributes and handle images
        $body = $dom->getElementsByTagName('body')->item(0) ?: $dom;
        $this->sanitizeNode($body);

        // 4. Get cleaned HTML content
        $content = '';
        foreach ($body->childNodes as $child) {
            $content .= $dom->saveHTML($child);
        }

        return [
            'title' => $title,
            'content' => trim($content),
            'node' => $body
        ];
    }

    private function sanitizeNode(\DOMNode $node)
    {
        if ($node instanceof \DOMElement) {
            // Remove all attributes except essential ones (src, href)
            $attributes = [];
            foreach ($node->attributes as $attr) {
                $attributes[] = $attr->nodeName;
            }
            foreach ($attributes as $attrName) {
                if (!in_array($attrName, ['src', 'href'])) {
                    $node->removeAttribute($attrName);
                }
            }

            // Handle images: Convert to placeholder or ensure they don't break
            if ($node->tagName === 'img') {
                $src = $node->getAttribute('src');
                $node->setAttribute('alt', '[Image: ' . basename($src) . ']');
                // Optionally keep src but we don't store local EPUB images in DB
                // $node->removeAttribute('src'); 
            }
        }

        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                $this->sanitizeNode($child);
            }
        }
    }

    private function extractReadableText(\DOMNode $node): string
    {
        $text = '';
        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMText) {
                $text .= $child->textContent;
            } elseif ($child instanceof \DOMElement) {
                if ($child->tagName === 'br') {
                    $text .= "\n";
                } elseif (in_array($child->tagName, ['p', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'blockquote'])) {
                    $text .= "\n" . $this->extractReadableText($child) . "\n";
                } else {
                    $text .= $this->extractReadableText($child);
                }
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

        $decoded = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $decoded = str_replace(["\r\n", "\r"], "\n", $decoded);
        $decoded = preg_replace("/[ \t]+/u", ' ', $decoded);
        // Normalize multiple newlines to double newlines (paragraphed)
        $decoded = preg_replace("/\n{3,}/u", "\n\n", $decoded);

        return trim($decoded ?? '');
    }
}
