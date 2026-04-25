<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenreTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = ['Fantasy', 'Romance', 'Action', 'Adventure', 'Mystery', 'Sci-Fi', 'Horror', 'Slice of Life'];
        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre,
                'slug' => Str::slug($genre)
            ]);
        }

        $tags = ['Isekai', 'Overpowered', 'Cultivation', 'Magic', 'Reincarnation', 'School Life', 'Supernatural'];
        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag)
            ]);
        }
    }
}
