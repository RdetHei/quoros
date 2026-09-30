<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Novel;
use App\Models\NovelCharacter;
use App\Models\Chapter;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MassiveDummyNovelSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Cleanup existing data to avoid duplication and conflicts
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('chapters')->truncate();
        DB::table('novel_characters')->truncate();
        DB::table('genre_novel')->truncate();
        DB::table('novel_tag')->truncate();
        DB::table('novels')->truncate();
        DB::table('users')->truncate();
        DB::table('genres')->truncate();
        DB::table('tags')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create specific users for each role
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $writer = User::create([
            'name' => 'Pro Writer',
            'username' => 'writer',
            'email' => 'writer@example.com',
            'password' => Hash::make('password'),
            'role' => 'writer',
        ]);

        $user = User::create([
            'name' => 'Regular Reader',
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 2. Create Genres and Tags
        $genreNames = ['Action', 'Adventure', 'Comedy', 'Drama', 'Fantasy', 'Horror', 'Mystery', 'Romance', 'Sci-Fi', 'Slice of Life', 'Supernatural', 'Thriller', 'Isekai', 'Xianxia', 'Wuxia'];
        $genres = collect($genreNames)->map(fn($name) => Genre::create(['name' => $name, 'slug' => Str::slug($name)]));

        $tagNames = ['System', 'Reincarnation', 'Magic', 'Weak to Strong', 'Cultivation', 'Martial Arts', 'Game Elements', 'Dungeon', 'Urban Fantasy', 'Harem', 'Reverse Harem', 'Overpowered MC', 'Alchemy', 'Demons', 'Academy'];
        $tags = collect($tagNames)->map(fn($name) => Tag::create(['name' => $name, 'slug' => Str::slug($name)]));

        // 3. Generate 100 dummy novels so landing and project-update have plenty of data
        $titlePrefixes = ['Eclipse', 'Crimson', 'Moonlit', 'Iron', 'Arcane', 'Celestial', 'Shadow', 'Storm', 'Ember', 'Silver', 'Velvet', 'Obsidian', 'Nova', 'Raven', 'Golden', 'Dawn', 'Frost', 'Blazing', 'Thunder', 'Horizon'];
        $titleSuffixes = ['Ascension', 'Reborn', 'Legacy', 'Oath', 'Veil', 'Empire', 'Gate', 'Warden', 'Chronicle', 'Eclipse', 'Dawn', 'Hollow', 'Reign', 'Pact', 'Crown', 'Drift', 'Rift', 'Mirage', 'Bastion', 'Forge'];
        $coverUrls = [
            'https://images.unsplash.com/photo-1519669556878-63bdad8a1a49?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1614728263952-84ea256f9679?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1541562232579-512a21360020?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1618336753974-aae8e04506aa?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1560972550-aba3456b5564?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1528319725582-ddc0b6a27656?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1580477667995-2b94f01c9516?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1613376023733-0d743d20719b?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1559981421-3e0c0d712e3b?auto=format&fit=crop&w=800&q=80',
        ];
        $regions = ['Korea', 'Japan', 'China', 'Indonesia', 'Global'];
        $types = ['web_novel', 'light_novel', 'original'];
        $statuses = ['ongoing', 'complete', 'hiatus'];
        $usedSlugs = [];

        for ($i = 1; $i <= 100; $i++) {
            $prefix = $titlePrefixes[($i - 1) % count($titlePrefixes)];
            $suffix = $titleSuffixes[(int) floor(($i - 1) / count($titlePrefixes)) % count($titleSuffixes)];
            $title = sprintf('%s %s %s', $prefix, $suffix, $i);
            $description = 'A young protagonist rises through impossible odds, fights against destiny, and carves out a future that no one thought was possible. The story blends action, mystery, and emotional growth in a vivid world shaped by power, betrayal, and redemption.';
            $baseSlug = Str::slug($title);
            $slug = $baseSlug;
            $slugCounter = 2;
            while (isset($usedSlugs[$slug])) {
                $slug = $baseSlug . '-' . $slugCounter;
                $slugCounter++;
            }
            $usedSlugs[$slug] = true;

            $novel = Novel::create([
                'author_id' => $writer->id,
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'status' => $statuses[$i % count($statuses)],
                'type' => $types[$i % count($types)],
                'region' => $regions[$i % count($regions)],
                'language' => 'English',
                'content_rating' => ['everyone', 'teen', 'mature'][$i % 3],
                'cover_image_url' => $coverUrls[$i % count($coverUrls)],
                'view_count' => rand(1500, 95000),
                'rating_avg' => rand(35, 50) / 10,
                'is_featured' => $i <= 15,
            ]);

            $novel->genres()->attach($genres->random(rand(2, 4))->pluck('id'));
            $novel->tags()->attach($tags->random(rand(3, 6))->pluck('id'));

            for ($chapterNo = 1; $chapterNo <= rand(8, 14); $chapterNo++) {
                $chapterTitle = 'Chapter ' . $chapterNo . ': ' . ['Awakening', 'Trial', 'Rising', 'Breakthrough', 'Abyss', 'Vow', 'Confrontation', 'Revelation', 'Battle', 'Ascension'][($chapterNo - 1) % 10];
                Chapter::create([
                    'novel_id' => $novel->id,
                    'title' => $chapterTitle,
                    'slug' => Str::slug($title . ' chapter ' . $chapterNo),
                    'content' => '<p>' . $title . ' continues with a new turning point for the protagonist.</p><p>The world grows darker, the stakes rise, and every decision matters more than before.</p><p>' . Str::random(800) . '</p>',
                    'status' => 'published',
                    'published_at' => now()->subDays(rand(1, 90)),
                ]);
            }

            NovelCharacter::create([
                'novel_id' => $novel->id,
                'name' => 'Aster Vale',
                'role' => 'Main Character',
                'description' => 'The lead character begins a dangerous journey to challenge fate and rise above the strongest forces in the world.',
                'image_url' => 'https://images.unsplash.com/photo-1578632738980-422cc36e2ec9?q=80&w=300&auto=format&fit=crop',
                'sort_order' => 1,
            ]);

            NovelCharacter::create([
                'novel_id' => $novel->id,
                'name' => 'Liora Dusk',
                'role' => 'Main Heroine',
                'description' => 'A brilliant strategist whose perspective and loyalty give the protagonist the strength to carry on.',
                'image_url' => 'https://images.unsplash.com/photo-1580477667995-2b94f01c9516?q=80&w=300&auto=format&fit=crop',
                'sort_order' => 2,
            ]);
        }

        echo "Seeding completed successfully with 100 dummy novels!\n";
        echo "Admin: admin@example.com / password\n";
        echo "Writer: writer@example.com / password\n";
        echo "User: user@example.com / password\n";
    }
}
