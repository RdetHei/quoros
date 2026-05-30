<?php

namespace Database\Seeders;

use App\Models\ReadingHistory;
use App\Models\Novel;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReadingHistorySeeder extends Seeder
{
    public function run($historyPerUser = 15): void
    {
        if ($this->command) {
            $this->command->info("Seeding reading history...");
        }

        $novels = Novel::with('chapters')->get();
        $users = User::where('role', 'user')->get();

        if ($novels->isEmpty()) {
            (new NovelSeeder())->run(50);
            $novels = Novel::with('chapters')->get();
        }

        if ($users->isEmpty()) {
            (new UserSeeder())->run(50);
            $users = User::where('role', 'user')->get();
        }

        foreach ($users as $user) {
            $randomNovels = $novels->random(min($historyPerUser, $novels->count()));

            foreach ($randomNovels as $novel) {
                $chapter = $novel->chapters->random();

                ReadingHistory::firstOrCreate(
                    ['user_id' => $user->id, 'novel_id' => $novel->id],
                    [
                        'chapter_id' => $chapter?->id,
                        'created_at' => now()->subDays(rand(0, 30)),
                        'updated_at' => now()->subDays(rand(0, 30)),
                    ]
                );
            }
        }

        if ($this->command) {
            $this->command->info("Reading history seeded successfully!");
        }
    }
}
