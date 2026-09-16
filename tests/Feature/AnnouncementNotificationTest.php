<?php

namespace Tests\Feature;

use App\Enums\NotificationType;
use App\Models\InAppNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_announcement_creates_notification_for_every_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $users = User::factory()->count(3)->create();

        $this->actingAs($admin);

        $response = $this->post(route('admin.announcements.store'), [
            'title' => 'Pengumuman ujian',
            'content' => 'Akan ada pemeliharaan server pada pukul 22.00.',
            'type' => 'info',
            'is_active' => true,
            'link' => 'https://example.com/announcement',
        ]);

        $response->assertRedirect(route('admin.announcements.index'));

        $expectedUsers = User::query()->pluck('id')->all();
        $this->assertSame(count($expectedUsers), InAppNotification::query()
            ->where('type', NotificationType::Announcement->value)
            ->count());

        foreach ($expectedUsers as $userId) {
            $this->assertDatabaseHas('notifications', [
                'user_id' => $userId,
                'type' => NotificationType::Announcement->value,
            ]);
        }
    }

    public function test_notification_click_opens_detail_page_before_redirecting_to_link(): void
    {
        $user = User::factory()->create();
        $notification = InAppNotification::create([
            'user_id' => $user->id,
            'type' => NotificationType::Announcement,
            'data' => [
                'title' => 'Pemeliharaan server',
                'body' => 'Server akan dimatikan pada jam 02.00.',
                'url' => 'https://example.com/maintenance',
            ],
        ]);

        $this->actingAs($user);

        $response = $this->get(route('notifications.show', $notification));

        $response->assertOk();
        $response->assertSee('Pemeliharaan server');
        $response->assertSee('https://example.com/maintenance');
    }
}
