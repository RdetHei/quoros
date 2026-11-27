<?php

namespace App\Database;

use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Query\Grammars\MySqlGrammar;

/**
 * Koneksi MySQL yang sadar TiDB.
 *
 * TiDB melaporkan versi seperti "8.0.11-TiDB-v8.5.3-serverless", sehingga Laravel
 * menganggapnya MySQL < 8.0.11 dan memakai emulasi `@laravel_row` untuk LIMIT di
 * dalam eager loading. Emulasi itu mengandalkan urutan baris yang tidak dijamin
 * oleh database, akibatnya relasi dengan ->take(n) (mis. "chapter terbaru") bisa
 * mengembalikan baris yang salah — novel hasil bulk upload tetap memperlihatkan
 * chapter 1 di UI.
 *
 * TiDB mendukung window function, jadi paksa jalur row_number() yang deterministik
 * supaya ORDER BY pada eager loading benar-benar dihormati.
 */
class TiDbMySqlConnection extends MySqlConnection
{
    /**
     * @return \Illuminate\Database\Query\Grammars\MySqlGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return new class($this) extends MySqlGrammar {
            public function useLegacyGroupLimit($query)
            {
                if (str_contains($query->getConnection()->getServerVersion(), 'TiDB')) {
                    return false;
                }

                return parent::useLegacyGroupLimit($query);
            }
        };
    }
}
