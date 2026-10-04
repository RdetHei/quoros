<?php

namespace Tests\Feature;

use App\Models\Novel;
use App\Models\ReadingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReaderDashboardReadingTimeTest extends TestCase
{
    use RefreshDatabase;

    public function test_total_reading_hours_come_from_reading_sessions(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $novel = Novel::factory()->create();

        ReadingSession::create([
            'session_uuid' => '8de98a6f-f345-4a18-a14b-af5485969c42',
            'user_id' => $user->id,
            'novel_id' => $novel->id,
            'started_at' => now()->subHours(3),
            'last_active_at' => now(),
            'active_seconds' => 9000,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Total reading hours: 2.5');
    }
}