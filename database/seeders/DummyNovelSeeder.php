<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Novel;
use App\Models\NovelCharacter;
use App\Models\Chapter;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyNovelSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan ada user dengan role writer
        $writer = User::where('role', 'writer')->first() ?? User::factory()->create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'writer@example.com',
            'role' => 'writer',
        ]);

        // 2. Buat Novel (Hapus yang lama jika ada agar tidak error unique slug)
        $slug = Str::slug('Echoes of the Void');
        Novel::where('slug', $slug)->delete();

        $novel = Novel::create([
            'author_id' => $writer->id,
            'title' => 'Echoes of the Void',
            'alternative_title' => 'The Forgotten Archivist',
            'slug' => Str::slug('Echoes of the Void'),
            'description' => 'In a world where memories are traded like currency, a young archivist discovers a forgotten record that could unravel the fabric of existence. As the shadows of the past begin to bleed into the present, he must decide whether to protect the status quo or embrace the chaos of the truth.',
            'status' => 'ongoing',
            'type' => 'original',
            'region' => 'United States',
            'language' => 'English',
            'content_rating' => 'teen',
            'cover_image_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=1200&h=400&auto=format&fit=crop', // Realistic placeholder
            'cover_public_id' => 'dummy_cover_id',
            'view_count' => 1250,
            'rating_avg' => 4.8,
        ]);

        // 3. Tambahkan Genre & Tag
        $genres = Genre::whereIn('name', ['Fantasy', 'Mystery', 'Adventure'])->pluck('id');
        if ($genres->isEmpty()) {
            $genres = Genre::factory(2)->create()->pluck('id');
        }
        $novel->genres()->attach($genres);

        $tags = Tag::whereIn('name', ['Magic', 'Dark', 'Action'])->pluck('id');
        if ($tags->isEmpty()) {
            $tags = Tag::factory(3)->create()->pluck('id');
        }
        $novel->tags()->attach($tags);

        // 4. Tambahkan 1 Chapter
        Chapter::create([
            'novel_id' => $novel->id,
            'title' => 'Chapter 1: The Archivist Discovery',
            'slug' => 'chapter-1-the-archivist-discovery',
            'content' => '<p>The air in the Great Archive was thick with the scent of old parchment and bottled dreams. Elias moved silently between the towering shelves, his lantern casting long, flickering shadows against the cold stone floor.</p><p>"Another night, another thousand souls forgotten," he whispered to himself.</p><p>His task was simple: catalog the "Spent Memories"—the ones people sold to pay their debts or forget their tragedies. But tonight, tucked behind a jar of a child\'s summer afternoon, he found it. A black glass vial, unlabelled and radiating a strange, cold hum.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        // 5. Tambahkan Karakter
        NovelCharacter::create([
            'novel_id' => $novel->id,
            'name' => 'Elias Thorne',
            'role' => 'Protagonist',
            'description' => 'A young, observant archivist who has spent his life surrounded by other people\'s memories, yet possesses very few of his own.',
            'image_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=300&h=300&auto=format&fit=crop',
            'image_public_id' => 'dummy_char_1',
            'sort_order' => 0,
        ]);

        NovelCharacter::create([
            'novel_id' => $novel->id,
            'name' => 'Elara Vance',
            'role' => 'The Mysterious Seller',
            'description' => 'A woman who appeared at the Archive with no past and a vial that shouldn\'t exist.',
            'image_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=300&h=300&auto=format&fit=crop',
            'image_public_id' => 'dummy_char_2',
            'sort_order' => 1,
        ]);
    }
}
