<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    public function run($count = 15): void
    {
        if ($this->command) {
            $this->command->info("Seeding {$count} genres...");
        }

        $genreNames = [
            'Action', 'Adventure', 'Comedy', 'Drama', 'Fantasy',
            'Horror', 'Mystery', 'Romance', 'Sci-Fi', 'Slice of Life',
            'Supernatural', 'Thriller', 'Isekai', 'Xianxia', 'Wuxia',
            'Martial Arts', 'Game', 'Harem', 'Tragedy', 'Sports'
        ];

        $genresToCreate = array_slice($genreNames, 0, $count);

        foreach ($genresToCreate as $name) {
            Genre::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]);
        }

        if ($this->command) {
            $this->command->info("Genres seeded successfully!");
        }
    }
}
