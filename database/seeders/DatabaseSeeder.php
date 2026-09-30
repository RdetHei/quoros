<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GenreSeeder::class,
            TagSeeder::class,
            WriterSeeder::class,
            NovelSeeder::class,
            AnnouncementSeeder::class,
            GuideSeeder::class,
            ChaptersOrderSeeder::class,
        ]);
    }
}
