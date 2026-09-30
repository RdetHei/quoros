<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Genre;
use App\Models\Novel;
use App\Models\NovelCharacter;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MassiveDummyNovelSeeder extends Seeder
{
    private const NOVEL_COUNT = 199;
    private const CHAPTERS_PER_NOVEL = 10;

    public function run(): void
    {
        $this->clearCatalog();

        $writer = User::create([
            'name' => 'Quoros Writer',
            'username' => 'quoros_writer',
            'email' => 'writer@example.com',
            'password' => Hash::make('password'),
            'role' => 'writer',
        ]);

        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Regular Reader',
            'username' => 'reader',
            'email' => 'reader@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $genres = $this->createTaxonomy([
            'Action', 'Adventure', 'Comedy', 'Drama', 'Fantasy', 'Horror',
            'Mystery', 'Romance', 'Sci-Fi', 'Slice of Life', 'Supernatural',
            'Thriller', 'Isekai', 'Xianxia', 'Wuxia',
        ], Genre::class);

        $tags = $this->createTaxonomy([
            'System', 'Reincarnation', 'Magic', 'Weak to Strong', 'Cultivation',
            'Martial Arts', 'Game Elements', 'Dungeon', 'Urban Fantasy', 'Harem',
            'Reverse Harem', 'Overpowered MC', 'Alchemy', 'Demons', 'Academy',
            'Time Travel', 'Found Family', 'Royalty', 'Slow Burn', 'Survival',
        ], Tag::class);

        for ($number = 1; $number <= self::NOVEL_COUNT; $number++) {
            $title = $this->novelTitle($number);
            $novel = Novel::create([
                'author_id' => $writer->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => "A serialized fantasy adventure about destiny, friendship, and the choices made when an ordinary life meets an extraordinary world. Novel #{$number} follows a new cast through a growing mystery.",
                'status' => ['ongoing', 'complete', 'hiatus'][$number % 3],
                'type' => ['web_novel', 'light_novel'][$number % 2],
                'region' => ['Indonesia', 'Korea', 'Japan', 'China'][$number % 4],
                'language' => 'English',
                'content_rating' => ['everyone', 'teen', 'mature'][$number % 3],
                'view_count' => 1000 + ($number * 137),
                'rating_avg' => 3.5 + (($number % 16) / 10),
                'is_featured' => $number <= 12,
            ]);

            $novel->genres()->attach([
                $genres[($number - 1) % count($genres)],
                $genres[$number % count($genres)],
            ]);
            $novel->tags()->attach([
                $tags[($number - 1) % count($tags)],
                $tags[($number + 4) % count($tags)],
                $tags[($number + 9) % count($tags)],
            ]);

            $this->createChapters($novel, $tags[$number % count($tags)]->name);
            $this->createCharacters($novel, $number);
        }

        $this->command?->info('Seeded 199 novels, 1,990 chapters, genres, tags, and characters.');
    }

    private function clearCatalog(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (['chapters', 'novel_characters', 'genre_novel', 'novel_tag', 'novels', 'users', 'genres', 'tags'] as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function createTaxonomy(array $names, string $model): array
    {
        return collect($names)->map(fn (string $name) => $model::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]))->all();
    }

    private function novelTitle(int $number): string
    {
        $prefixes = [
            'The Clockwork', 'Chronicles of the', 'Rebirth of a', 'The Last',
            'A Scholar in the', 'The Wandering', 'Rise of the', 'My Secret',
            'The Silent', 'Beyond the', 'The Forgotten', 'Dawn of the',
            'The Alchemist and the', 'A Promise Beneath the', 'The Unchosen',
        ];
        $subjects = [
            'Moon Kingdom', 'Azure Empire', 'Starbound City', 'Eternal Forest',
            'Crimson Tower', 'Hidden Academy', 'Fallen Dynasty', 'Glass Sea',
            'Dragon Archive', 'Midnight Frontier', 'Silver Labyrinth',
            'Hollow Throne', 'Skyforge Valley', 'Winter Observatory',
        ];

        return sprintf('%s %s %03d', $prefixes[($number - 1) % count($prefixes)], $subjects[($number - 1) % count($subjects)], $number);
    }

    private function createChapters(Novel $novel, string $featuredTag): void
    {
        for ($number = 1; $number <= self::CHAPTERS_PER_NOVEL; $number++) {
            Chapter::create([
                'novel_id' => $novel->id,
                'title' => sprintf('Chapter %d: %s', $number, $this->chapterTitle($number)),
                'slug' => Str::slug($novel->slug . '-chapter-' . $number),
                'content' => "<p>The journey of <strong>{$novel->title}</strong> continues.</p><p>New clues point toward the {$featuredTag} hidden behind the next turning point.</p><p>Chapter {$number} brings the characters closer to the truth.</p>",
                'status' => 'published',
                'published_at' => now()->subDays(self::CHAPTERS_PER_NOVEL - $number),
                'order' => $number,
            ]);
        }
    }

    private function chapterTitle(int $number): string
    {
        return [
            1 => 'The First Omen',
            2 => 'A Door Opens',
            3 => 'Unexpected Allies',
            4 => 'The Hidden Map',
            5 => 'Trial by Fire',
            6 => 'Echoes of the Past',
            7 => 'Into the Unknown',
            8 => 'A Price to Pay',
            9 => 'The Truth Revealed',
            10 => 'A New Beginning',
        ][$number];
    }

    private function createCharacters(Novel $novel, int $number): void
    {
        NovelCharacter::create([
            'novel_id' => $novel->id,
            'name' => "Ari {$number}",
            'role' => 'Main Character',
            'description' => 'A determined protagonist who must learn to trust their companions.',
            'sort_order' => 1,
        ]);

        NovelCharacter::create([
            'novel_id' => $novel->id,
            'name' => "Mira {$number}",
            'role' => 'Companion',
            'description' => 'A clever companion who brings a different view to every difficult choice.',
            'sort_order' => 2,
        ]);
    }
}
