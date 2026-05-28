<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Review;
use App\Models\ReadingHistory;
use App\Models\Novel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StatsSeeder extends Seeder
{
    public function run(): void
    {
        $writer = User::where('role', 'writer')->first();
        if (!$writer) return;

        $novels = $writer->novels;
        $users = User::where('id', '!=', $writer->id)->take(50)->get();

        if ($users->isEmpty()) {
            $users = User::factory(20)->create();
        }

        foreach ($novels as $novel) {
            // Create random bookmarks over the last 30 days
            foreach ($users->random(rand(5, 15)) as $user) {
                $date = Carbon::now()->subDays(rand(0, 30));
                Bookmark::updateOrCreate(
                    ['user_id' => $user->id, 'novel_id' => $novel->id],
                    ['created_at' => $date, 'updated_at' => $date]
                );
            }

            // Create random reviews over the last 30 days
            foreach ($users->random(rand(2, 8)) as $user) {
                $date = Carbon::now()->subDays(rand(0, 30));
                Review::updateOrCreate(
                    ['user_id' => $user->id, 'novel_id' => $novel->id],
                    [
                        'rating' => rand(3, 5),
                        'content' => 'Great story! Really enjoying the development.',
                        'created_at' => $date,
                        'updated_at' => $date
                    ]
                );
            }

            // Create random reading histories (daily readers proxy)
            foreach ($users->random(rand(10, 20)) as $user) {
                $date = Carbon::now()->subDays(rand(0, 30));
                ReadingHistory::updateOrCreate(
                    ['user_id' => $user->id, 'novel_id' => $novel->id],
                    [
                        'chapter_id' => $novel->chapters()->first()?->id,
                        'created_at' => $date,
                        'updated_at' => $date
                    ]
                );
            }
        }
    }
}
