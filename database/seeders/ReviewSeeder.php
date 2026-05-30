<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Novel;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run($reviewsPerNovel = 5): void
    {
        if ($this->command) {
            $this->command->info("Seeding reviews...");
        }

        $novels = Novel::all();
        $users = User::where('role', 'user')->get();

        if ($novels->isEmpty()) {
            (new NovelSeeder())->run(50);
            $novels = Novel::all();
        }

        if ($users->isEmpty()) {
            (new UserSeeder())->run(50);
            $users = User::where('role', 'user')->get();
        }

        $reviewContents = [
            'Great story! I really enjoyed reading it.',
            'The plot is amazing and the characters are well-developed.',
            'Can\'t wait for the next chapter!',
            'This is one of the best novels I\'ve read.',
            'The world-building is incredible.',
            'The main character is relatable and grows throughout the story.',
            'Highly recommend this novel to everyone!',
            'The author does a fantastic job with the descriptions.',
            'I couldn\'t put it down!',
            'Definitely worth reading.',
        ];

        foreach ($novels as $novel) {
            $randomUsers = $users->random(min($reviewsPerNovel, $users->count()));

            foreach ($randomUsers as $user) {
                Review::firstOrCreate(
                    ['user_id' => $user->id, 'novel_id' => $novel->id],
                    [
                        'rating' => rand(3, 5),
                        'content' => $reviewContents[array_rand($reviewContents)],
                        'created_at' => now()->subDays(rand(0, 30)),
                    ]
                );
            }
        }

        if ($this->command) {
            $this->command->info("Reviews seeded successfully!");
        }
    }
}
