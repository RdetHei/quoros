<?php

namespace App\Services;

use App\Support\ParagraphReflow;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Component\Process\Process;

class DocxParserService
{
    public function __construct(private DocumentSegmenter $segmenter) {}

    public function parse(string $filePath): array
    {
        if (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) === 'doc') {
            return $this->parseLegacyDoc($filePath);
        }

        $document = IOFactory::load($filePath);
        $blocks = [];

        foreach ($document->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text = $this->normalizeText($this->elementText($element));
                if ($text === '') {
                    continue;
                }

                $headingLevel = $this->headingLevel($element);
                $blocks[] = [
                    'text' => $text,
                    'heading_level' => $headingLevel,
                    'source' => 'docx',
                    'style' => $this->styleName($element),
                    'bold' => $this->isBold($element),
                ];
            }
        }

        return $this->segmenter->segment($blocks);
    }

    private function parseLegacyDoc(string $filePath): array
    {
        $text = $this->extractLegacyDocText($filePath);
        $blocks = [];

        foreach (ParagraphReflow::paragraphs($text) as $paragraph) {
            $paragraph = $this->normalizeText($paragraph);
            if ($paragraph !== '') {
                $blocks[] = ['text' => $paragraph, 'source' => 'legacy_doc'];
            }
        }

        return $this->segmenter->segment($blocks);
    }

    private function extractLegacyDocText(string $filePath): string
    {
        foreach ([['antiword', $filePath]] as $command) {
            try {
                $process = new Process($command);
                $process->setTimeout(120);
                $process->run();
                if ($process->isSuccessful() && trim($process->getOutput()) !== '') {
                    return $process->getOutput();
                }
            } catch (\Throwable) {
                // Try the office converter below.
            }
        }

        $directory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'quoros-doc-'.bin2hex(random_bytes(8));
        if (! mkdir($directory) && ! is_dir($directory)) {
            throw new \RuntimeException('Tidak dapat membuat direktori sementara untuk membaca DOC.');
        }

        try {
            $process = new Process(['soffice', '--headless', '--convert-to', 'txt:Text', '--outdir', $directory, $filePath]);
            $process->setTimeout(120);
            $process->run();
            $convertedPath = $directory.DIRECTORY_SEPARATOR.pathinfo($filePath, PATHINFO_FILENAME).'.txt';

            if ($process->isSuccessful() && is_file($convertedPath)) {
                return (string) file_get_contents($convertedPath);
            }
        } catch (\Throwable) {
            // Return one actionable error after both optional converters fail.
        } finally {
            foreach (glob($directory.DIRECTORY_SEPARATOR.'*') ?: [] as $temporaryFile) {
                @unlink($temporaryFile);
            }
            @rmdir($directory);
        }

        throw new \RuntimeException('File DOC lama memerlukan antiword atau LibreOffice/soffice di server. Simpan ulang sebagai DOCX atau pasang salah satu alat tersebut.');
    }

    private function headingLevel(object $element): ?int
    {
        if ($element instanceof Title) {
            $depth = (int) $element->getDepth();
            return $depth > 0 ? $depth : null;
        }

        $style = $this->styleName($element);
        if ($style && preg_match('/(?:heading|titre|berschrift|titulo)[\s_-]*([1-9])/iu', $style, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function styleName(object $element): ?string
    {
        if (! method_exists($element, 'getParagraphStyle')) {
            return null;
        }

        $style = $element->getParagraphStyle();
        if (is_string($style)) {
            return $style;
        }

        if (is_object($style)) {
            foreach (['getStyleName', 'getName'] as $method) {
                if (method_exists($style, $method)) {
                    $name = $style->{$method}();
                    if (is_string($name)) {
                        return $name;
                    }
                }
            }
        }

        return null;
    }

    private function isBold(object $element): bool
    {
        if (method_exists($element, 'getFontStyle')) {
            $style = $element->getFontStyle();
            return is_object($style) && method_exists($style, 'isBold') && $style->isBold();
        }

        return false;
    }

    private function elementText(object $element): string
    {
        if ($element instanceof TextBreak) {
            return "\n";
        }

        if (method_exists($element, 'getText')) {
            $text = $element->getText();
            if (is_string($text)) {
                return $text;
            }
        }

        if (! method_exists($element, 'getElements')) {
            return '';
        }

        $text = '';
        foreach ($element->getElements() as $child) {
            if (is_object($child)) {
                $text .= $this->elementText($child);
            }
        }

        return $text;
    }

    private function normalizeText(string $text): string
    {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/[\t ]+/u', ' ', $text) ?? $text;
        $text = preg_replace('/\n{3,}/u', "\n\n", $text) ?? $text;

        return trim($text);
    }
}
