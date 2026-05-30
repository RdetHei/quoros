<?php

namespace Database\Seeders;

use App\Models\NovelCharacter;
use App\Models\Novel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NovelCharacterSeeder extends Seeder
{
    public function run($charactersPerNovel = 2): void
    {
        if ($this->command) {
            $this->command->info("Seeding novel characters...");
        }

        $novels = Novel::all();

        if ($novels->isEmpty()) {
            (new NovelSeeder())->run(50);
            $novels = Novel::all();
        }

        $characterRoles = ['Main Character', 'Main Heroine', 'Support Character', 'Antagonist', 'Rival'];
        $characterNames = [
            'Alex', 'Luna', 'Kai', 'Sakura', 'Leo', 'Mia', 'Noah', 'Zoe',
            'Ethan', 'Aria', 'Liam', 'Hana', 'Mason', 'Yuki', 'Logan'
        ];

        foreach ($novels as $novel) {
            for ($i = 0; $i < $charactersPerNovel; $i++) {
                NovelCharacter::create([
                    'novel_id' => $novel->id,
                    'name' => $characterNames[array_rand($characterNames)] . ' ' . Str::random(5),
                    'role' => $characterRoles[$i % count($characterRoles)],
                    'description' => $this->generateCharacterDescription(),
                    'image_url' => $this->getRandomUnsplashPhoto('portrait,anime'),
                    'sort_order' => $i + 1,
                ]);
            }
        }

        if ($this->command) {
            $this->command->info("Novel characters seeded successfully!");
        }
    }

    private function generateCharacterDescription(): string
    {
        $descriptions = [
            'The main protagonist who embarks on an epic journey to save the world.',
            'A loyal companion who supports the hero through thick and thin.',
            'The main antagonist whose ambition threatens the peace of the realm.',
            'A mysterious figure with hidden motives and incredible power.',
            'A rival who pushes the protagonist to become stronger every day.',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomUnsplashPhoto(string $keyword = 'portrait'): string
    {
        $seed = Str::random(8);
        return "https://images.unsplash.com/seed-{$seed}/photo?auto=format&fit=crop&w=400&q=80";
    }
}
