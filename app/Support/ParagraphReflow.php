<?php

namespace App\Support;

/**
 * Mengubah teks hasil ekstraksi dokumen (PDF/DOCX/EPUB/.doc) yang masih berupa
 * deretan baris mentah menjadi paragraf yang wajar.
 *
 * Ekstraktor dokumen umumnya mengembalikan teks sebagai baris-baris: baris
 * terpotong karena lebar kolom, sedangkan batas paragraf asli hanya terlihat
 * dari baris terakhir yang lebih pendek atau diakhiri tanda baca akhir kalimat.
 * Helper ini menggabungkan baris yang sebenarnya masih satu paragraf dan
 * memisahkan yang memang paragraf berbeda, sehingga teks tidak tersimpan
 * sebagai satu blok raksasa.
 */
class ParagraphReflow
{
    /**
     * Ubah teks multi-baris menjadi daftar paragraf yang sudah digabung.
     *
     * @return list<string>
     */
    public static function paragraphs(string $text): array
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $lines = explode("\n", $text);

        $paragraphs = [];
        foreach (self::groupLineRanges($lines) as [$start, $end]) {
            $parts = [];
            for ($i = $start; $i <= $end; $i++) {
                $value = trim($lines[$i]);
                if ($value !== '') {
                    $parts[] = $value;
                }
            }

            if ($parts !== []) {
                $paragraphs[] = self::joinLines($parts);
            }
        }

        return $paragraphs;
    }

    /**
     * Kelompokkan baris mentah menjadi rentang indeks tiap paragraf.
     *
     * @param  list<string>  $lines
     * @return list<array{0:int,1:int}> rentang [barisAwal, barisAkhir] inklusif
     */
    public static function groupLineRanges(array $lines): array
    {
        $clean = [];
        foreach ($lines as $index => $line) {
            $clean[$index] = trim(preg_replace('/[\t ]+/u', ' ', (string) $line) ?? (string) $line);
        }

        $width = self::columnWidth($clean);

        $ranges = [];
        $start = null;
        $previous = null;

        foreach ($clean as $index => $line) {
            if ($line === '' || preg_match('/^\d{1,4}$/u', $line)) {
                if ($start !== null) {
                    $ranges[] = [$start, $previous];
                    $start = null;
                }
                $previous = null;
                continue;
            }

            if ($start === null) {
                $start = $index;
                $previous = $index;
                continue;
            }

            if (self::startsNewParagraph($clean[$previous], $line, $width)) {
                $ranges[] = [$start, $previous];
                $start = $index;
            }

            $previous = $index;
        }

        if ($start !== null) {
            $ranges[] = [$start, $previous];
        }

        return $ranges;
    }

    /**
     * Perkirakan lebar kolom teks (dalam karakter) dari panjang baris yang
     * paling sering muncul. Mengembalikan 0 bila pola lebar tidak konsisten,
     * supaya pemanggil tidak memakai heuristik lebar.
     *
     * @param  array<int, string>  $lines
     */
    public static function columnWidth(array $lines): int
    {
        $lengths = [];
        foreach ($lines as $line) {
            $length = mb_strlen($line);
            if ($length >= 20) {
                $lengths[] = $length;
            }
        }

        if (count($lengths) < 6) {
            return 0;
        }

        $buckets = [];
        foreach ($lengths as $length) {
            $bucket = (int) (floor($length / 4) * 4);
            $buckets[$bucket] = ($buckets[$bucket] ?? 0) + 1;
        }

        arsort($buckets);
        $mode = (int) array_key_first($buckets);

        // Lebar kolom hanya dipercaya bila mayoritas baris panjangnya seragam.
        if ($buckets[$mode] / count($lengths) < 0.35) {
            return 0;
        }

        return $mode + 3;
    }

    /**
     * Tentukan apakah baris saat ini memulai paragraf baru.
     *
     * @param  int  $width  Lebar kolom hasil {@see self::columnWidth()}, 0 bila tidak diketahui.
     */
    public static function startsNewParagraph(string $previous, string $current, int $width): bool
    {
        // Penanda daftar/bullet selalu memulai paragraf baru.
        if (preg_match('/^(?:[●•◦▪▫■□◆◇·]|[-–—*]\s|\d{1,3}[.)]\s)/u', $current)) {
            return true;
        }

        // Baris sebelumnya jauh lebih pendek dari lebar kolom => akhir paragraf.
        if ($width > 0 && mb_strlen($previous) < (int) round($width * 0.85)) {
            return true;
        }

        // Cadangan: baris sebelumnya menutup kalimat, baris berikut membuka kalimat baru.
        return (bool) preg_match('/[.!?…]["\'”’)\]]?\s*$/u', $previous)
            && (bool) preg_match('/^(?:["“\'‘(\[]|\p{Lu})/u', $current);
    }

    /** @param list<string> $lines */
    private static function joinLines(array $lines): string
    {
        $text = '';
        foreach ($lines as $index => $line) {
            if ($index === 0) {
                $text = $line;
                continue;
            }

            // Gabungkan kata yang terpotong tanda hubung di akhir baris.
            $text = preg_replace('/\x{00AD}$/u', '', $text) ?? $text;
            $text = preg_replace('/([^\s])-$/u', '$1', $text) ?? $text;
            $text = rtrim($text);

            $text .= ' '.trim($line);
        }

        return trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
    }
}
