<?php

namespace Tests\Feature;

use App\Models\Novel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserListTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_reading_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('lists.store'), [
            'title' => 'Weekend Reads',
            'description' => 'Stories for a quiet weekend.',
            'is_public' => '1',
        ]);

        $list = $user->userLists()->firstOrFail();

        $response->assertRedirect(route('lists.show', $list));
        $this->assertDatabaseHas('user_lists', [
            'user_id' => $user->id,
            'title' => 'Weekend Reads',
            'slug' => 'weekend-reads',
            'is_public' => true,
        ]);
    }

    public function test_edit_page_keeps_update_and_delete_forms_separate(): void
    {
        $user = User::factory()->create();
        $list = $user->userLists()->create([
            'title' => 'Weekend Reads',
            'slug' => 'weekend-reads',
        ]);

        $response = $this->actingAs($user)->get(route('lists.edit', $list));

        $response->assertOk();
        $response->assertSee('id="update-list-form"', false);
        $response->assertSee('form="update-list-form"', false);
        $response->assertSee('id="delete-list-form"', false);
    }

    public function test_list_detail_can_render_add_novel_form_without_novel_route_parameter(): void
    {
        $user = User::factory()->create();
        $novel = Novel::factory()->create();
        $list = $user->userLists()->create([
            'title' => 'Weekend Reads',
            'slug' => 'weekend-reads',
        ]);

        $response = $this->actingAs($user)->get(route('lists.show', $list));

        $response->assertOk();
        $response->assertSee('action="' . route('lists.novels.add', ['list' => $list]) . '"', false);

        $this->actingAs($user)
            ->post(route('lists.novels.add', ['list' => $list]), ['novel_id' => $novel->id])
            ->assertRedirect();

        $this->assertTrue($list->novels()->whereKey($novel->id)->exists());
    }

    public function test_reading_list_index_loads_list_novel_previews(): void
    {
        $user = User::factory()->create();
        $user->userLists()->create([
            'title' => 'Weekend Reads',
            'slug' => 'weekend-reads',
        ]);

        $response = $this->actingAs($user)->get(route('lists.index'));

        $response->assertOk();
        $response->assertSee('Weekend Reads');
    }

    public function test_profile_loads_reading_list_previews(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'username' => 'list-owner',
        ]);
        $user->userLists()->create([
            'title' => 'Weekend Reads',
            'slug' => 'weekend-reads',
        ]);

        $response = $this->actingAs($user)->get(route('profile.show', $user->username));

        $response->assertOk();
        $response->assertSee('Weekend Reads');
    }

    public function test_public_list_detail_links_back_to_its_owner_profile(): void
    {
        $owner = User::factory()->create(['username' => 'list-owner']);
        $list = $owner->userLists()->create([
            'title' => 'Weekend Reads',
            'slug' => 'weekend-reads',
            'is_public' => true,
        ]);

        $response = $this->get(route('lists.public', [$owner->username, $list]));

        $response->assertOk();
        $response->assertSee(route('profile.show', $owner->username), false);
        $response->assertSee('Back to Profile');
    }
}