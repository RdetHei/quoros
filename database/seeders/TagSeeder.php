<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run($count = 20): void
    {
        if ($this->command) {
            $this->command->info("Seeding {$count} tags...");
        }

        $tagNames = [
            'System', 'Reincarnation', 'Magic', 'Weak to Strong', 'Cultivation',
            'Martial Arts', 'Game Elements', 'Dungeon', 'Urban Fantasy', 'Harem',
            'Reverse Harem', 'Overpowered MC', 'Alchemy', 'Demons', 'Academy',
            'Time Travel', 'Virtual Reality', 'Apocalypse', 'Romance', 'Adventure',
            'Comedy', 'Tragedy', 'Superhero', 'Vampire', 'Werewolf'
        ];

        $tagsToCreate = array_slice($tagNames, 0, $count);

        foreach ($tagsToCreate as $name) {
            Tag::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]);
        }

        if ($this->command) {
            $this->command->info("Tags seeded successfully!");
        }
    }
}
