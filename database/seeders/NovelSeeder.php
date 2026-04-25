<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\Novel;
use App\Models\Review;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class NovelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $writers = User::where('role', 'writer')->get();
        $readers = User::where('role', 'user')->get();
        $genres = Genre::all();
        $tags = Tag::all();

        if ($writers->isEmpty()) {
            return;
        }

        $writers->each(function ($writer) use ($genres, $tags, $readers) {
            $novels = Novel::factory(rand(2, 4))->create([
                'author_id' => $writer->id,
            ]);

            $novels->each(function ($novel) use ($genres, $tags, $readers) {
                // Attach random genres and tags
                $novel->genres()->attach($genres->random(rand(1, 3))->pluck('id'));
                $novel->tags()->attach($tags->random(rand(2, 5))->pluck('id'));

                // Create Chapters for each novel
                $chapters = Chapter::factory(rand(5, 15))->create([
                    'novel_id' => $novel->id,
                ]);

                // Create Reviews for each novel from readers
                Review::factory(rand(3, 8))->create([
                    'novel_id' => $novel->id,
                    'user_id' => $readers->random()->id,
                ]);

                // Create Comments for each chapter from readers
                $chapters->each(function ($chapter) use ($readers) {
                    Comment::factory(rand(0, 5))->create([
                        'chapter_id' => $chapter->id,
                        'user_id' => $readers->random()->id,
                    ]);
                });
            });
        });
    }
}
