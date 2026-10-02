<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Novel;
use App\Models\ReadingHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageForYouTest extends TestCase
{
    use RefreshDatabase;

    public function test_for_you_only_section_renders_for_guest(): void
    {
        $novels = Novel::factory()->count(4)->create();
        foreach ($novels as $novel) {
            Chapter::factory()->create([
                'novel_id' => $novel->id,
                'status' => 'published',
                'published_at' => now()->subDay(),
            ]);
        }

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('FOR YOU ONLY');
        $response->assertViewHas('forYou', fn ($forYou) => $forYou->count() === 4);
    }

    public function test_for_you_only_section_renders_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $novels = Novel::factory()->count(4)->create();
        foreach ($novels as $novel) {
            Chapter::factory()->create([
                'novel_id' => $novel->id,
                'status' => 'published',
                'published_at' => now()->subDay(),
            ]);
        }

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('FOR YOU ONLY');
        $response->assertViewHas('forYou', fn ($forYou) => $forYou->count() === 4);
    }

    public function test_for_you_falls_back_when_user_has_read_all_novels(): void
    {
        $user = User::factory()->create();
        $novels = Novel::factory()->count(4)->create();
        foreach ($novels as $novel) {
            $chapter = Chapter::factory()->create([
                'novel_id' => $novel->id,
                'status' => 'published',
                'published_at' => now()->subDay(),
            ]);
            ReadingHistory::create([
                'user_id' => $user->id,
                'novel_id' => $novel->id,
                'chapter_id' => $chapter->id,
                'last_read_at' => now(),
            ]);
        }

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('FOR YOU ONLY');
        $response->assertViewHas('forYou', fn ($forYou) => $forYou->count() === 4);
    }
}
