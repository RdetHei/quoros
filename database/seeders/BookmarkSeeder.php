<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Novel;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    public function run($bookmarksPerUser = 10): void
    {
        if ($this->command) {
            $this->command->info("Seeding bookmarks...");
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

        foreach ($users as $user) {
            $randomNovels = $novels->random(min($bookmarksPerUser, $novels->count()));

            foreach ($randomNovels as $novel) {
                Bookmark::firstOrCreate(
                    ['user_id' => $user->id, 'novel_id' => $novel->id],
                    ['created_at' => now()->subDays(rand(0, 30))]
                );
            }
        }

        if ($this->command) {
            $this->command->info("Bookmarks seeded successfully!");
        }
    }
}
