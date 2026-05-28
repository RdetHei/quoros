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

class MassiveDummyNovelSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure writer user exists
        $writer = User::where('role', 'writer')->first() ?? User::factory()->create([
            'name' => 'Elite Writer',
            'username' => 'elitewriter',
            'email' => 'elite@example.com',
            'role' => 'writer',
        ]);

        $genres = Genre::all();
        $tags = Tag::all();

        if ($genres->isEmpty()) {
            $genres = collect(['Fantasy', 'Mystery', 'Adventure', 'Sci-Fi', 'Romance', 'Action', 'Drama', 'Horror', 'Supernatural', 'Psychological'])->map(fn($name) => Genre::create(['name' => $name, 'slug' => Str::slug($name)]));
        }
        
        if ($tags->isEmpty()) {
            $tags = collect(['Magic', 'Dark', 'System', 'Rebirth', 'WeakToStrong', 'Cultivation', 'Dungeon', 'Comedy', 'SchoolLife', 'Urban'])->map(fn($name) => Tag::create(['name' => $name, 'slug' => Str::slug($name)]));
        }

        $titles = [
            'Beyond the Stellar Gate', 'Chronicles of the Iron Soul', 'Whispers from the Abyss', 'The Last Alchemist of Eir',
            'Shadows of the Neon City', 'Dawn of the Dragon King', 'The Silent Weaver', 'Path of the Nameless Sage',
            'Echoes of Eternal Night', 'The Void Walker', 'Empire of Shattered Glass', 'Guardians of the Ancient Flame',
            'Requiem for a Fallen God', 'The Clockwork Heart', 'Blade of the Moonlight', 'Secrets of the Floating Isles',
            'The Serpent and the Star', 'Legends of the Silver Plains', 'Tides of Chaos', 'The Archivist of Dreams',
            'Rune of the Forgotten', 'The Gilded Cage', 'Fires of Rebellion', 'Ghost in the Machine',
            'The Obsidian Throne', 'Winds of Destiny', 'Cursed Bloodline', 'The Infinite Labyrinth',
            'Sunlight through the Mist', 'The Frozen Kingdom', 'Tale of the Wandering Knight', 'Legacy of the Phoenix',
            'The Crimson Veil', 'Dungeons of Despair', 'Celestial Sovereign', 'The Weaver of Fates',
            'Heart of the Storm', 'The Emerald Dragon', 'Shadow Play', 'The Midnight Library',
            'Shattered Reality', 'The Golden Compass', 'Bonds of Steel', 'The Alchemist\'s Debt',
            'Witch of the Black Woods', 'The Iron Rose', 'Starlight Odyssey', 'The Hidden Continent',
            'Rise of the Fallen', 'The Eternal Voyager'
        ];

        $synopses = [
            'A journey through space and time where every choice matters.',
            'In a world of steam and gears, one man must find his humanity.',
            'Dark secrets lurk in the depths of the ocean, waiting to be found.',
            'The last practitioner of an ancient art must save his world from ruin.',
            'Cybernetic enhancements come at a heavy price in this neon-lit dystopia.',
            'The return of an ancient ruler marks the beginning of a new era.',
            'She weaves the threads of reality itself, but at what cost?',
            'A master of martial arts seeks enlightenment in a world of war.',
            'When the sun never rises, the shadows become your only friends.',
            'Stepping into the void is easy; coming back is the hard part.',
            'A kingdom built on lies is destined to shatter like glass.',
            'Ancient guardians wake from their slumber to face a new threat.',
            'Even gods can die, and their passing leaves a void that must be filled.',
            'A mechanical heart beats with the rhythm of a lost love.',
            'Under the moonlight, the blade sings a song of vengeance.',
            'Floating high above the clouds, these islands hold forgotten treasures.',
            'The cosmic balance rests on the shoulders of a simple shepherd.',
            'On the vast silver plains, legends are born and died in a day.',
            'Chaos is a ladder, and some are born to climb it.',
            'Collecting dreams is a dangerous business in a world of nightmares.',
            'A single rune holds the power to rewrite history.',
            'Beauty can be a prison, and the bars are made of gold.',
            'Rebellion starts with a spark, but it takes fire to win.',
            'In a world of code, a ghost is the most dangerous variable.',
            'The obsidian throne demands a blood sacrifice from its heir.',
            'Destiny is a fickle mistress, blowing like the wind.',
            'A family secret that has haunted generations finally comes to light.',
            'The labyrinth is endless, and every door leads to a new mystery.',
            'Light finds a way even in the thickest of fog.',
            'In the heart of winter, a kingdom struggles for survival.',
            'The knight wanders the world, seeking a purpose he lost long ago.',
            'The phoenix rises once every thousand years to cleanse the world.',
            'Behind the crimson veil lies a truth no one is ready to face.',
            'To survive the dungeon, one must become more than human.',
            'Ascending to the heavens is only the beginning of the struggle.',
            'She sees the future in the patterns of the stars.',
            'The storm is coming, and only the strong will survive its wrath.',
            'A dragon born of emerald scales holds the key to life.',
            'Every shadow tells a story, if you know how to listen.',
            'A library where every book is a gateway to another world.',
            'Reality is a fragile thing, easily broken by a single thought.',
            'The compass points to the thing you desire most, but never to home.',
            'In the heat of battle, bonds are forged that can never be broken.',
            'Transmutation always requires a sacrifice of equal value.',
            'The woods are dark and full of terrors, and she is the queen.',
            'A rose that blooms in iron is a symbol of hope in dark times.',
            'A voyage across the stars in search of a new home for humanity.',
            'A continent hidden from the world for millennia is finally revealed.',
            'The fallen will rise again, and this time, they will not be defeated.',
            'Traveling through the eternity of time, searching for a single moment.'
        ];

        foreach ($titles as $index => $title) {
            $slug = Str::slug($title);
            
            // Cleanup existing if any
            Novel::where('slug', $slug)->delete();

            $novel = Novel::create([
                'author_id' => $writer->id,
                'title' => $title,
                'slug' => $slug,
                'description' => $synopses[$index],
                'status' => collect(['ongoing', 'complete', 'hiatus'])->random(),
                'type' => collect(['web_novel', 'light_novel', 'original'])->random(),
                'region' => collect(['Japan', 'Korea', 'China', 'USA', 'Europe'])->random(),
                'language' => 'English',
                'content_rating' => collect(['everyone', 'teen', 'mature'])->random(),
                'cover_image_url' => "https://picsum.photos/seed/" . Str::random(10) . "/1200/400",
                'cover_public_id' => 'massive_dummy_' . $index,
                'view_count' => rand(100, 10000),
                'rating_avg' => rand(30, 50) / 10,
                'is_featured' => $index < 5, // Make first 5 featured
            ]);

            // Attach Genres & Tags
            $novel->genres()->attach($genres->random(rand(1, 3))->pluck('id'));
            $novel->tags()->attach($tags->random(rand(2, 5))->pluck('id'));

            // 1 Chapter
            Chapter::create([
                'novel_id' => $novel->id,
                'title' => 'Chapter 1: The Beginning',
                'slug' => 'chapter-1-the-beginning-' . $index . '-' . Str::random(5),
                'content' => '<p>This is the start of an epic tale titled ' . $title . '.</p><p>' . $synopses[$index] . '</p>',
                'status' => 'published',
                'published_at' => now(),
            ]);

            // 2 Characters
            NovelCharacter::create([
                'novel_id' => $novel->id,
                'name' => 'Protagonist of ' . $index,
                'role' => 'Main Character',
                'description' => 'A brave individual starting their journey.',
                'image_url' => "https://picsum.photos/seed/" . Str::random(10) . "/300/300",
                'image_public_id' => 'char_a_' . $index,
                'sort_order' => 0,
            ]);

            NovelCharacter::create([
                'novel_id' => $novel->id,
                'name' => 'Companion of ' . $index,
                'role' => 'Sidekick',
                'description' => 'A loyal friend through thick and thin.',
                'image_url' => "https://picsum.photos/seed/" . Str::random(10) . "/300/300",
                'image_public_id' => 'char_b_' . $index,
                'sort_order' => 1,
            ]);

            echo "Seeded: " . ($index + 1) . "/50 - " . $title . "\n";
        }
    }
}
