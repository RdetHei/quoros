<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\UserSeeder;
use Database\Seeders\GenreSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\NovelSeeder;
use Database\Seeders\ChapterSeeder;
use Database\Seeders\NovelCharacterSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\BookmarkSeeder;
use Database\Seeders\ReadingHistorySeeder;
use Database\Seeders\AnnouncementSeeder;
use Database\Seeders\GuideSeeder;

class SeedData extends Command
{
    protected $signature = 'db:seed-data {--user=50 : Number of users to seed} {--novel=50 : Number of novels to seed} {--chapter=10 : Number of chapters per novel} {--genre=15 : Number of genres to seed} {--tag=20 : Number of tags to seed} {--review=5 : Number of reviews per novel} {--bookmark=10 : Number of bookmarks per user} {--character=2 : Number of characters per novel} {--fresh : Refresh database before seeding}';

    protected $description = 'Seed database with customizable options';

    public function handle(): int
    {
        $this->info('Starting data seeding...');

        if ($this->option('fresh')) {
            $this->info('Refreshing database...');
            Artisan::call('migrate:fresh', [], $this->output);
            $this->info('Database refreshed successfully!');
        }

        $options = [
            'users' => (int) $this->option('user'),
            'novels' => (int) $this->option('novel'),
            'chapters' => (int) $this->option('chapter'),
            'genres' => (int) $this->option('genre'),
            'tags' => (int) $this->option('tag'),
            'reviews' => (int) $this->option('review'),
            'bookmarks' => (int) $this->option('bookmark'),
            'characters' => (int) $this->option('character'),
        ];

        $this->seedAll($options);

        $this->info('');
        $this->info('Seeding completed successfully!');
        $this->info('Login credentials:');
        $this->info('- Admin: admin@example.com / password');
        $this->info('- Writer: writer@example.com / password');
        $this->info('- User: user@example.com / password');

        return self::SUCCESS;
    }

    private function seedAll(array $options): void
    {
        $userSeeder = new UserSeeder();
        $userSeeder->setCommand($this);
        $userSeeder->run($options['users']);

        $genreSeeder = new GenreSeeder();
        $genreSeeder->setCommand($this);
        $genreSeeder->run($options['genres']);

        $tagSeeder = new TagSeeder();
        $tagSeeder->setCommand($this);
        $tagSeeder->run($options['tags']);

        $novelSeeder = new NovelSeeder();
        $novelSeeder->setCommand($this);
        $novelSeeder->run($options['novels']);

        $chapterSeeder = new ChapterSeeder();
        $chapterSeeder->setCommand($this);
        $chapterSeeder->run($options['chapters']);

        $characterSeeder = new NovelCharacterSeeder();
        $characterSeeder->setCommand($this);
        $characterSeeder->run($options['characters']);

        $reviewSeeder = new ReviewSeeder();
        $reviewSeeder->setCommand($this);
        $reviewSeeder->run($options['reviews']);

        $bookmarkSeeder = new BookmarkSeeder();
        $bookmarkSeeder->setCommand($this);
        $bookmarkSeeder->run($options['bookmarks']);

        $historySeeder = new ReadingHistorySeeder();
        $historySeeder->setCommand($this);
        $historySeeder->run(15);

        $announcementSeeder = new AnnouncementSeeder();
        $announcementSeeder->setCommand($this);
        $announcementSeeder->run();

        $guideSeeder = new GuideSeeder();
        $guideSeeder->setCommand($this);
        $guideSeeder->run();
    }
}
