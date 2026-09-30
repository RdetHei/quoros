<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WriterSeeder extends Seeder
{
    private const WRITER_COUNT = 50;

    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['username' => 'reader'],
            ['name' => 'Regular Reader', 'email' => 'reader@example.com', 'password' => Hash::make('password'), 'role' => 'user']
        );

        $firstNames = ['Alya', 'Bima', 'Citra', 'Damar', 'Elara', 'Farhan', 'Gita', 'Hana', 'Indra', 'Jihan', 'Kirana', 'Laras', 'Mika', 'Nara', 'Orion', 'Putri', 'Raka', 'Sasha', 'Tara', 'Vino'];
        $lastNames = ['Ananta', 'Pradana', 'Wicaksono', 'Mahendra', 'Suryani', 'Nugraha', 'Kusuma', 'Permata', 'Wijaya', 'Adiputra'];

        for ($number = 1; $number <= self::WRITER_COUNT; $number++) {
            $username = sprintf('seed_writer_%02d', $number);

            User::firstOrCreate(
                ['username' => $username],
                [
                    'name' => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                    'email' => sprintf('seed_writer_%02d@example.test', $number),
                    'password' => Hash::make('password'),
                    'role' => 'writer',
                    'bio' => 'Penulis yang senang berbagi cerita dan dunia baru.',
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command?->info('Seeded 50 writer accounts (password: password).');
    }
}
