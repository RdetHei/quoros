<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Chapter;
use App\Models\Novel;
use App\Models\NovelCharacter;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NovelSeeder extends Seeder
{
    private const NOVEL_COUNT = 100;
    private const WRITERS_WITH_NOVELS = 35;

    public function run(): void
    {
        $writers = User::where('role', 'writer')->orderBy('username')->get();

        if ($writers->count() < 50) {
            $this->call(WriterSeeder::class);
            $writers = User::where('role', 'writer')->orderBy('username')->get();
        }

        $genres = Genre::all();
        $tags = Tag::all();

        if ($genres->isEmpty()) {
            $this->call(GenreSeeder::class);
            $genres = Genre::all();
        }

        if ($tags->isEmpty()) {
            $this->call(TagSeeder::class);
            $tags = Tag::all();
        }

        // Pick only 35 writers to guarantee that some writers have no novels.
        $activeWriters = $writers->shuffle()->take(self::WRITERS_WITH_NOVELS)->values();
        $assignments = $activeWriters->pluck('id')->all();

        // Give each selected writer at least one novel, then randomize the rest.
        while (count($assignments) < self::NOVEL_COUNT) {
            $assignments[] = $activeWriters->random()->id;
        }
        shuffle($assignments);

        $titleStarts = ['The', 'A', 'Chronicles of', 'Beneath the', 'Beyond the', 'Echoes of', 'The Last', 'Dawn of'];
        $titleSubjects = ['Azure Moon', 'Silent Kingdom', 'Clockwork Garden', 'Forgotten Star', 'Silver River', 'Hidden Academy', 'Winter Crown', 'Dragon Archive', 'Midnight City', 'Wandering Swordsman'];
        $genres = $genres->values();
        $tags = $tags->values();

        foreach ($assignments as $index => $authorId) {
            $number = $index + 1;
            $title = $titleStarts[array_rand($titleStarts)] . ' ' . $titleSubjects[array_rand($titleSubjects)] . ' ' . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
            $slug = 'seed-' . Str::slug($title);
            $novel = Novel::firstOrCreate(
                ['slug' => $slug],
                [
                    'author_id' => $authorId,
                    'title' => $title,
                    'description' => "A serialized story about an unexpected journey, unlikely allies, and the choices that shape a new world. Story entry {$number}.",
                    'status' => ['ongoing', 'complete', 'hiatus'][array_rand(['ongoing', 'complete', 'hiatus'])],
                    'type' => ['web_novel', 'light_novel', 'original'][array_rand(['web_novel', 'light_novel', 'original'])],
                    'region' => ['Indonesia', 'Korea', 'Japan', 'China', 'Western'][array_rand(['Indonesia', 'Korea', 'Japan', 'China', 'Western'])],
                    'language' => 'English',
                    'content_rating' => ['everyone', 'teen', 'mature'][array_rand(['everyone', 'teen', 'mature'])],
                    'view_count' => random_int(0, 50000),
                    'rating_avg' => random_int(0, 50) / 10,
                    'is_featured' => random_int(0, 1) === 1,
                ]
            );

            if ($novel->wasRecentlyCreated) {
                $novel->genres()->sync($genres->isEmpty() ? [] : $genres->random(min(random_int(1, 3), $genres->count()))->pluck('id'));
                $novel->tags()->sync($tags->isEmpty() ? [] : $tags->random(min(random_int(1, 4), $tags->count()))->pluck('id'));
                $this->createChapters($novel, random_int(3, 10));
                $this->createCharacters($novel, $number);
            }
        }

        $this->command?->info('Seeded 100 novels with random chapters across 35 randomly selected writers.');
    }

    private function createChapters(Novel $novel, int $count): void
    {
        $chapterTitles = [
            'The First Omen', 'A Door Opens', 'Unexpected Allies', 'The Hidden Map',
            'Trial by Fire', 'Echoes of the Past', 'Into the Unknown', 'A Price to Pay',
            'The Truth Revealed', 'A New Beginning',
        ];

        for ($number = 1; $number <= $count; $number++) {
            $title = sprintf('Chapter %d: %s', $number, $chapterTitles[($number - 1) % count($chapterTitles)]);

            Chapter::create([
                'novel_id' => $novel->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => "<p>The story of <strong>{$novel->title}</strong> continues.</p><p>New clues and unexpected choices lead the characters toward their next challenge.</p><p>Chapter {$number} brings them one step closer to the truth.</p>",
                'status' => 'published',
                'published_at' => now()->subDays($count - $number),
                'order' => $number,
            ]);
        }
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
