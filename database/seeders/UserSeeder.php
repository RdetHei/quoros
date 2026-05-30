<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run($count = 50): void
    {
        if ($this->command) {
            $this->command->info("Seeding {$count} users...");
        }

        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'bio' => 'Administrator of the platform',
            'profile_photo_url' => $this->getRandomUnsplashPhoto('portrait'),
        ]);

        User::create([
            'name' => 'Pro Writer',
            'username' => 'writer',
            'email' => 'writer@example.com',
            'password' => Hash::make('password'),
            'role' => 'writer',
            'bio' => 'Professional novel writer',
            'profile_photo_url' => $this->getRandomUnsplashPhoto('portrait'),
        ]);

        User::create([
            'name' => 'Regular Reader',
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'bio' => 'Avid novel reader',
            'profile_photo_url' => $this->getRandomUnsplashPhoto('portrait'),
        ]);

        if ($count > 3) {
            User::factory($count - 3)->create()->each(function ($user) {
                $user->profile_photo_url = $this->getRandomUnsplashPhoto('portrait');
                $user->save();
            });
        }

        if ($this->command) {
            $this->command->info("Users seeded successfully!");
        }
    }

    private function getRandomUnsplashPhoto(string $keyword = 'people'): string
    {
        $seed = Str::random(8);
        return "https://images.unsplash.com/seed-{$seed}/photo?auto=format&fit=crop&w=400&q=80";
    }
}
