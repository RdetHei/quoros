<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Announcement::create([
            'title' => 'Papan Pengumuman Baru',
            'content' => 'Selamat datang di dashboard baru Mural! Kami baru saja memperbarui sistem ulasan. Sekarang Anda bisa memberikan rating bintang hanya dengan sekali klik. Selamat berkarya!',
            'type' => 'info',
            'is_active' => true,
        ]);
    }
}
