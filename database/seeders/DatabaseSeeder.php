<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MassiveDummyNovelSeeder::class,
            AnnouncementSeeder::class,
            GuideSeeder::class,
            ChaptersOrderSeeder::class,
        ]);
    }
}
