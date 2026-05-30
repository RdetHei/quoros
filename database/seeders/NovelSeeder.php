<?php

namespace Database\Seeders;

use App\Models\Novel;
use App\Models\User;
use App\Models\Genre;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NovelSeeder extends Seeder
{
    public function run($count = 50): void
    {
        if ($this->command) {
            $this->command->info("Seeding {$count} novels...");
        }

        $writer = User::where('role', 'writer')->first();
        if (!$writer) {
            $writer = User::factory()->create(['role' => 'writer']);
        }

        $genres = Genre::all();
        $tags = Tag::all();

        if ($genres->isEmpty()) {
            (new GenreSeeder())->run(15);
            $genres = Genre::all();
        }

        if ($tags->isEmpty()) {
            (new TagSeeder())->run(20);
            $tags = Tag::all();
        }

        $novelTitles = $this->getNovelTitles();
        $regions = ['Korea', 'Japan', 'China', 'Western'];
        $types = ['web_novel', 'light_novel'];
        $statuses = ['ongoing', 'complete', 'hiatus'];
        $ratings = ['everyone', 'teen', 'mature'];

        for ($i = 0; $i < $count; $i++) {
            $title = $novelTitles[$i % count($novelTitles)];
            $suffix = $i >= count($novelTitles) ? ' ' . ($i - count($novelTitles) + 2) : '';

            $novel = Novel::create([
                'author_id' => $writer->id,
                'title' => $title . $suffix,
                'slug' => Str::slug($title . $suffix),
                'description' => $this->generateDescription(),
                'status' => $statuses[array_rand($statuses)],
                'type' => $types[array_rand($types)],
                'region' => $regions[array_rand($regions)],
                'language' => 'English',
                'content_rating' => $ratings[array_rand($ratings)],
                'cover_image_url' => $this->getRandomUnsplashPhoto('book,novel,anime'),
                'view_count' => rand(100, 50000),
                'rating_avg' => rand(30, 50) / 10,
                'is_featured' => $i < 10,
            ]);

            $novel->genres()->attach($genres->random(rand(2, 4))->pluck('id'));
            $novel->tags()->attach($tags->random(rand(3, 6))->pluck('id'));
        }

        if ($this->command) {
            $this->command->info("Novels seeded successfully!");
        }
    }

    private function getNovelTitles(): array
    {
        return [
            'The Solo Leveling God',
            'Reincarnation of the Heavenly Demon',
            'My S-Rank Skill is Infinite Mana',
            'The Alchemist of the Eternal Empire',
            'Shadow Sovereign: Path to Immortality',
            'That Time I Became a Dungeon Master',
            'Legend of the Moonlight Sculptor',
            'Desolate Era: The Beginning of the End',
            'Sword Art of the Falling Stars',
            'The Villainess Wants to Live a Peaceful Life',
            'Necromancer of the Apocalypse',
            'Rise of the Undead Legion',
            'The System Makes Me OP',
            'Cultivation: From Zero to Hero',
            'The Magic Academy Reborn',
            'My Life as a Side Character',
            'The Hidden Blade Master',
            'Demon King Retires',
            'The Spirit Tamer',
            'Isekai: The Slow Life',
            'Martial Peak',
            'Battle Through the Heavens',
            'Perfect World',
            'The Beginning After the End',
            'Release That Witch',
            'The King\'s Avatar',
            'Quan Zhi Gao Shou',
            'Soul Land',
            'Stellar Transformations',
            'Coiling Dragon',
        ];
    }

    private function generateDescription(): string
    {
        $templates = [
            'In a world where {system} determines one\'s fate, our protagonist discovers a hidden power that will change everything.',
            'Betrayed and left for dead, {name} awakens in a new world with a second chance at life and revenge.',
            'When the apocalypse arrived, {name} was given a unique system that allows them to grow stronger with every battle.',
            'Transported to another world, {name} must use their knowledge from the modern world to survive in a land of magic and monsters.',
            'The weakest in the academy, {name} discovers a legendary inheritance that will make them the strongest.',
        ];

        $systems = ['the System', 'levels', 'cultivation', 'magic', 'dungeon gates'];
        $names = ['he', 'she', 'they', 'our hero'];

        $template = $templates[array_rand($templates)];
        $system = $systems[array_rand($systems)];
        $name = $names[array_rand($names)];

        return str_replace(['{system}', '{name}'], [$system, $name], $template) . ' ' . Str::random(200);
    }

    private function getRandomUnsplashPhoto(string $keyword = 'anime'): string
    {
        $keywords = ['anime', 'book', 'fantasy', 'art', 'illustration', 'manga', 'novel'];
        $keyword = $keywords[array_rand($keywords)];
        $seed = Str::random(8);
        return "https://images.unsplash.com/seed-{$seed}/photo?auto=format&fit=crop&w=800&q=80";
    }
}
