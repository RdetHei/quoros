<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChapterSeeder extends Seeder
{
    public function run($chaptersPerNovel = 10): void
    {
        if ($this->command) {
            $this->command->info("Seeding chapters...");
        }

        $novels = Novel::all();

        if ($novels->isEmpty()) {
            (new NovelSeeder())->run(50);
            $novels = Novel::all();
        }

        foreach ($novels as $novel) {
            for ($i = 1; $i <= $chaptersPerNovel; $i++) {
                $chapterTitles = [
                    'The Awakening',
                    'First Battle',
                    'The Hidden Power',
                    'Unexpected Encounter',
                    'Training Arc',
                    'The Tournament Begins',
                    'Revelation',
                    'Betrayal',
                    'New Allies',
                    'The Final Showdown',
                ];

                $title = "Chapter {$i}: " . ($chapterTitles[$i - 1] ?? 'Development');

                Chapter::create([
                    'novel_id' => $novel->id,
                    'title' => $title,
                    'slug' => Str::slug($novel->title . ' ' . $title),
                    'content' => $this->generateChapterContent($novel->title, $i),
                    'status' => 'published',
                    'published_at' => now()->subDays($chaptersPerNovel - $i),
                ]);
            }
        }

        if ($this->command) {
            $this->command->info("Chapters seeded successfully!");
        }
    }

    private function generateChapterContent(string $novelTitle, int $chapterNumber): string
    {
        $content = "<p><strong>{$novelTitle}</strong> - Chapter {$chapterNumber}</p>";
        $content .= '<p>The story continues as our protagonist faces new challenges. With each step, they grow stronger and uncover more secrets about the world they inhabit.</p>';
        $content .= '<p>As the sun sets over the horizon, a figure emerges from the shadows. "You\'re late," a voice echoes through the empty street. Our hero turns around, hand already on the hilt of their weapon.</p>';
        $content .= '<p>' . Str::random(500) . '</p>';
        $content .= '<p>' . Str::random(300) . '</p>';
        $content .= '<p>And so, the chapter comes to an end. But the journey has only just begun...</p>';

        return $content;
    }
}
