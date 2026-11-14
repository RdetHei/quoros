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

        // PDF tidak menyimpan struktur heading, jadi pemisahan chapter
        // sepenuhnya mengandalkan penanda teks (Chapter 1, Bab 2, dst).
        // Helper yang sama dipakai agar hasilnya konsisten dengan DOCX.
        $chapters = NovelParserService::splitByKeywords($text);

        // Jika tidak ada penanda sama sekali, simpan sebagai satu chapter utuh.
        if (empty($chapters)) {
            $chapters[] = [
                'title' => 'Full Story',
                'content' => trim($text),
            ];
        }

        return $chapters;
    }
}
