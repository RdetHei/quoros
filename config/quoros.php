<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Batas Permintaan Halaman Baca
    |--------------------------------------------------------------------------
    |
    | Jumlah maksimum permintaan halaman baca chapter per menit untuk satu IP.
    | Dipakai oleh RateLimiter "chapter-read" di AppServiceProvider.
    | Nilai ini dibaca lewat config() (bukan env()) supaya tetap benar ketika
    | konfigurasi di-cache di production.
    |
    */

    'chapter_read_per_minute' => (int) env('CHAPTER_READ_PER_MINUTE', 45),

];
