<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Novel;
use App\Models\Chapter;

class ChaptersOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Novel::chunk(100, function ($novels) {
            foreach ($novels as $novel) {
                $i = 0;

                Chapter::where('novel_id', $novel->id)
                    ->orderBy('id')
                    ->chunk(100, function ($chapters) use (&$i) {
                        foreach ($chapters as $chapter) {
                            $chapter->order = $i++;
                            $chapter->save();
                        }
                    });
            }
        });
    }
}
