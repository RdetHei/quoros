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

        // 3. Anime-style Novel Data (English titles for Korean/Japanese/Chinese style novels)
        $novelsData = [
            [
                'title' => 'The Solo Leveling God',
                'description' => 'In a world where gates connect our world to dungeons, E-rank hunter Sung Jin-Woo is the weakest of them all. But everything changes when he finds a hidden dungeon.',
                'region' => 'Korea',
                'type' => 'web_novel',
                'cover' => 'https://images.unsplash.com/photo-1519669556878-63bdad8a1a49?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Reincarnation of the Heavenly Demon',
                'description' => 'The strongest demonic practitioner is betrayed by his disciples and reincarnates into the body of a weak noble youth. Now, he will rise again.',
                'region' => 'China',
                'type' => 'web_novel',
                'cover' => 'https://images.unsplash.com/photo-1614728263952-84ea256f9679?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'My S-Rank Skill is Infinite Mana',
                'description' => 'After being summoned to another world, Sato receives a seemingly useless skill. However, he soon realizes its potential is limitless.',
                'region' => 'Japan',
                'type' => 'light_novel',
                'cover' => 'https://images.unsplash.com/photo-1541562232579-512a21360020?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'The Alchemist of the Eternal Empire',
                'description' => 'A modern-day genius chemist is transported to a world where alchemy is the foundation of power. He decides to revolutionize the industry.',
                'region' => 'China',
                'type' => 'web_novel',
                'cover' => 'https://images.unsplash.com/photo-1618336753974-aae8e04506aa?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Shadow Sovereign: Path to Immortality',
                'description' => 'In a world governed by a mysterious System, one man chooses the path of shadows to protect his family from the impending apocalypse.',
                'region' => 'Korea',
                'type' => 'web_novel',
                'cover' => 'https://images.unsplash.com/photo-1560972550-aba3456b5564?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'That Time I Became a Dungeon Master',
                'description' => 'Dying in a freak accident, our protagonist wakes up as the core of a newly formed dungeon. Now he must build his domain to survive.',
                'region' => 'Japan',
                'type' => 'light_novel',
                'cover' => 'https://images.unsplash.com/photo-1528319725582-ddc0b6a27656?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Legend of the Moonlight Sculptor',
                'description' => 'Lee Hyun is a legendary gamer who sells his character for billions. But fate has other plans for him in the new VR game, Royal Road.',
                'region' => 'Korea',
                'type' => 'web_novel',
                'cover' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Desolate Era: The Beginning of the End',
                'description' => 'Ning is born into a world of immortals and monsters. With his past life memories, he seeks to reach the pinnacle of cultivation.',
                'region' => 'China',
                'type' => 'web_novel',
                'cover' => 'https://images.unsplash.com/photo-1614728263952-84ea256f9679?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Sword Art of the Falling Stars',
                'description' => 'A young boy finds a rusty sword that contains the soul of an ancient sword god. His journey to the top begins now.',
                'region' => 'Japan',
                'type' => 'light_novel',
                'cover' => 'https://images.unsplash.com/photo-1519669556878-63bdad8a1a49?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'The Villainess Wants to Live a Peaceful Life',
                'description' => 'Reincarnated as the villainess of a popular otome game, Katarina decides to avoid all destruction flags by becoming a master of agriculture.',
                'region' => 'Japan',
                'type' => 'light_novel',
                'cover' => 'https://images.unsplash.com/photo-1580477667995-2b94f01c9516?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Necromancer of the Apocalypse',
                'description' => 'When the world turns into a game, Han-Seong awakens the hidden class of Necromancer. He will command an army of the dead to survive.',
                'region' => 'Korea',
                'type' => 'web_novel',
                'cover' => 'https://images.unsplash.com/photo-1613376023733-0d743d20719b?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Rise of the Undead Legion',
                'description' => 'Dave is a regular gamer who finds himself trapped in a skeleton body. He must lead his fellow undead to conquer the VR world.',
                'region' => 'China',
                'type' => 'web_novel',
                'cover' => 'https://images.unsplash.com/photo-1559981421-3e0c0d712e3b?auto=format&fit=crop&w=800&q=80',
            ]
        ];

        foreach ($novelsData as $index => $data) {
            $novel = Novel::create([
                'author_id' => $writer->id,
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'description' => $data['description'],
                'status' => collect(['ongoing', 'complete', 'hiatus'])->random(),
                'type' => $data['type'],
                'region' => $data['region'],
                'language' => 'English',
                'content_rating' => collect(['everyone', 'teen', 'mature'])->random(),
                'cover_image_url' => $data['cover'],
                'view_count' => rand(1000, 50000),
                'rating_avg' => rand(35, 50) / 10,
                'is_featured' => $index < 6,
            ]);

            // Attach random genres and tags
            $novel->genres()->attach($genres->random(rand(2, 4))->pluck('id'));
            $novel->tags()->attach($tags->random(rand(3, 6))->pluck('id'));

            // Create chapters for each novel
            for ($i = 1; $i <= 10; $i++) {
                Chapter::create([
                    'novel_id' => $novel->id,
                    'title' => "Chapter $i: " . ($i === 1 ? 'The Awakening' : ($i === 10 ? 'Conclusion of the Prologue' : 'Development')),
                    'slug' => Str::slug($data['title'] . " chapter $i"),
                    'content' => "<p>This is the content for chapter $i of <strong>" . $data['title'] . "</strong>.</p><p>The story continues as our protagonist faces new challenges and grows stronger through the " . $tags->random()->name . " system.</p><p>" . Str::random(500) . "</p>",
                    'status' => 'published',
                    'published_at' => now()->subDays(10 - $i),
                ]);
            }

            // Create some characters
            NovelCharacter::create([
                'novel_id' => $novel->id,
                'name' => 'Protagonist Name',
                'role' => 'Main Character',
                'description' => 'The main lead who possesses a unique system and seeks to reach the top.',
                'image_url' => 'https://images.unsplash.com/photo-1578632738980-422cc36e2ec9?q=80&w=300&auto=format&fit=crop',
                'sort_order' => 1,
            ]);

            NovelCharacter::create([
                'novel_id' => $novel->id,
                'name' => 'Support Heroine',
                'role' => 'Main Heroine',
                'description' => 'A loyal companion who supports the protagonist throughout their journey.',
                'image_url' => 'https://images.unsplash.com/photo-1580477667995-2b94f01c9516?q=80&w=300&auto=format&fit=crop',
                'sort_order' => 2,
            ]);
        }

        echo "Seeding completed successfully with anime-style LN/Webnovels!\n";
        echo "Admin: admin@example.com / password\n";
        echo "Writer: writer@example.com / password\n";
        echo "User: user@example.com / password\n";
    }
}
