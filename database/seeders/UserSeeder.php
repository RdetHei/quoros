<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@mural.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Writers
        User::factory(5)->create([
            'role' => 'writer',
            'password' => Hash::make('password'),
        ]);

        // Create Users (Readers)
        User::factory(10)->create([
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);
    }
}
