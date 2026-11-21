<?php

namespace App\Services;

use ZipArchive;
use DOMDocument;
use DOMXPath;
use DOMNode;
use Throwable;

class EpubParserService
{
    public function __construct(private DocumentSegmenter $segmenter) {}

    public function parse(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \RuntimeException('Gagal membuka file EPUB.');
        }

        try {
            $containerContent = $zip->getFromName('META-INF/container.xml');
            if (! $containerContent) {
                throw new \RuntimeException('EPUB tidak memiliki META-INF/container.xml.');
            }

            $containerDom = new DOMDocument();
            if (! @$containerDom->loadXML($containerContent, LIBXML_NONET)) {
                throw new \RuntimeException('Struktur container EPUB rusak.');
            }
            $rootfile = $containerDom->getElementsByTagName('rootfile')->item(0);
            if (! $rootfile) {
                throw new \RuntimeException('EPUB tidak memiliki file OPF.');
            }

            $opfPath = $this->normalizePath($rootfile->getAttribute('full-path'));
            $opfContent = $zip->getFromName($opfPath);
            if (! $opfContent) {
                throw new \RuntimeException('File OPF EPUB tidak dapat dibaca.');
            }

            $opfDom = new DOMDocument();
            if (! @$opfDom->loadXML($opfContent, LIBXML_NONET)) {
                throw new \RuntimeException('XML OPF EPUB rusak.');
            }
            $xpath = new DOMXPath($opfDom);
            $xpath->registerNamespace('dc', 'http://purl.org/dc/elements/1.1/');
            $metadata = [
                'title' => trim((string) $xpath->query('//*[local-name()="title" and namespace-uri()="http://purl.org/dc/elements/1.1/"]')->item(0)?->textContent),
                'author' => trim((string) $xpath->query('//*[local-name()="creator" and namespace-uri()="http://purl.org/dc/elements/1.1/"]')->item(0)?->textContent),
                'description' => trim((string) $xpath->query('//*[local-name()="description" and namespace-uri()="http://purl.org/dc/elements/1.1/"]')->item(0)?->textContent),
                'language' => trim((string) $xpath->query('//*[local-name()="language" and namespace-uri()="http://purl.org/dc/elements/1.1/"]')->item(0)?->textContent),
            ];

            $manifest = [];
            $tocItems = [];
            $items = $opfDom->getElementsByTagName('item');
            foreach ($items as $item) {
                $id = $item->getAttribute('id');
                $properties = preg_split('/\s+/', trim($item->getAttribute('properties'))) ?: [];
                $manifest[$id] = [
                    'href' => $item->getAttribute('href'),
                    'media_type' => $item->getAttribute('media-type'),
                    'properties' => $properties,
                ];
                if ($item->getAttribute('media-type') === 'application/x-dtbncx+xml' || in_array('nav', $properties, true)) {
                    $tocItems[$id] = $manifest[$id];
                }
            }

            $tocTitles = [];
            foreach ($tocItems as $tocItem) {
                $tocPath = $this->resolvePath(dirname($opfPath), $tocItem['href']);
                $tocContent = $zip->getFromName($tocPath);
                if ($tocContent) {
                    $tocTitles += $this->parseToc($tocContent, $tocItem['media_type'], dirname($tocPath));
                }
            }

            $blocks = [];
            foreach ($opfDom->getElementsByTagName('itemref') as $itemref) {
                if (strtolower($itemref->getAttribute('linear')) === 'no') {
                    continue;
                }

                $item = $manifest[$itemref->getAttribute('idref')] ?? null;
                if (! $item || ! str_contains($item['media_type'], 'html') || in_array('nav', $item['properties'], true)) {
                    continue;
                }

                $path = $this->resolvePath(dirname($opfPath), $item['href']);
                $content = $zip->getFromName($path);
                if (! $content) {
                    continue;
                }

                $cleaned = $this->cleanHtmlContent($content);
                $itemBlocks = $this->extractBlocks($cleaned['node']);
                if ($itemBlocks === []) {
                    continue;
                }

                $navTitle = $tocTitles[$path] ?? null;
                $firstText = trim((string) ($itemBlocks[0]['text'] ?? ''));
                if ($navTitle && ! $this->isFrontMatterTitle($navTitle)) {
                    if ($this->sameTitle($firstText, $navTitle)) {
                        $itemBlocks[0]['text'] = $navTitle;
                        $itemBlocks[0]['heading_level'] = 1;
                        $itemBlocks[0]['nav_boundary'] = true;
                        $itemBlocks[0]['source'] = 'epub_navigation';
                    } else {
                        array_unshift($itemBlocks, [
                            'text' => $navTitle,
                            'heading_level' => 1,
                            'nav_boundary' => true,
                            'source' => 'epub_navigation',
                        ]);
                    }
                }

                array_push($blocks, ...$itemBlocks);
            }

            $chapters = $this->segmenter->segment($blocks);
            if ($chapters !== []) {
                $chapters[0]['metadata'] = $metadata;
            }

            return $chapters;
        } finally {
            $zip->close();
        }
    }

    private function parseToc(string $content, string $mediaType, string $tocDirectory): array
    {
        $titles = [];
        $dom = new DOMDocument();
        @$dom->loadXML($content, LIBXML_NONET);
        $xpath = new DOMXPath($dom);

        if ($mediaType === 'application/x-dtbncx+xml') {
            $navPoints = $xpath->query('//*[local-name()="navPoint"]');
            foreach ($navPoints as $point) {
                $label = $xpath->query('./*[local-name()="navLabel"]/*[local-name()="text"]', $point)->item(0)?->textContent;
                $src = $xpath->query('./*[local-name()="content"]', $point)->item(0)?->getAttribute('src');
                if ($label && $src) {
                    $titles[$this->resolvePath($tocDirectory, $src)] = trim($label);
                }
            }
        } else {
            @$dom->loadHTML('<?xml encoding="UTF-8">'.$content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
            $xpath = new DOMXPath($dom);
            $links = $xpath->query('//*[local-name()="nav" and (contains(@*[local-name()="type"], "toc") or @type="toc")]//*[local-name()="a"]');
            foreach ($links as $link) {
                $label = $link->textContent;
                $src = $link->getAttribute('href');
                if ($label && $src) {
                    $titles[$this->resolvePath($tocDirectory, $src)] = trim($label);
                }
            }
        }

        return $titles;
    }

    private function resolvePath(string $directory, string $href): string
    {
        $href = rawurldecode((string) (parse_url($href, PHP_URL_PATH) ?? $href));
        if (str_starts_with($href, '/')) {
            $directory = '';
            $href = ltrim($href, '/');
        }

        return $this->normalizePath(trim($directory.'/'.$href, '/'));
    }

    private function normalizePath(string $path): string
    {
        $parts = [];
        foreach (explode('/', str_replace('\\', '/', rawurldecode($path))) as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }
            if ($part === '..') {
                array_pop($parts);
                continue;
            }
            $parts[] = $part;
        }

        return implode('/', $parts);
    }

    /** @return list<array<string, mixed>> */
    private function extractBlocks(DOMNode $node): array
    {
        $blocks = [];
        $this->collectBlocks($node, $blocks);

        return $blocks;
    }

    /** @param list<array<string, mixed>> $blocks */
    private function collectBlocks(DOMNode $node, array &$blocks): void
    {
        foreach ($node->childNodes as $child) {
            if (! $child instanceof \DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);
            if (preg_match('/^h([1-6])$/', $tag, $matches)) {
                $text = $this->extractReadableText($child);
                if ($text !== '') {
                    $blocks[] = ['text' => $text, 'heading_level' => (int) $matches[1], 'source' => 'epub_heading'];
                }
                continue;
            }

            if (in_array($tag, ['p', 'li', 'blockquote'], true)) {
                $text = $this->extractReadableText($child);
                if ($text !== '') {
                    $blocks[] = ['text' => $text, 'source' => 'epub_paragraph'];
                }
                continue;
            }

            $this->collectBlocks($child, $blocks);
        }
    }

    private function isFrontMatterTitle(string $title): bool
    {
        return preg_match('/^(cover|title page|copyright|contents|table of contents|daftar isi|nav|halaman judul)$/iu', trim($title)) === 1;
    }

    private function sameTitle(string $left, string $right): bool
    {
        $normalize = fn (string $title) => mb_strtolower(preg_replace('/[^\p{L}\p{N}]+/u', '', $title) ?? $title);

        return $normalize($left) !== '' && $normalize($left) === $normalize($right);
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
