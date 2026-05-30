<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GenreSeeder::class,
            TagSeeder::class,
            NovelSeeder::class,
            ChapterSeeder::class,
            NovelCharacterSeeder::class,
            ReviewSeeder::class,
            BookmarkSeeder::class,
            ReadingHistorySeeder::class,
            AnnouncementSeeder::class,
            GuideSeeder::class,
        ]);
    }
}
