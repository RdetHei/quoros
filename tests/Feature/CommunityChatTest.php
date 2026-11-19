<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\ChatMessage;
use App\Models\Comment;
use App\Models\Novel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_the_public_lounge(): void
    {
        ChatMessage::factory()->create(['body' => 'Halo lounge']);

        $this->get(route('chat.index'))
            ->assertOk()
            ->assertSee('Lounge Publik')
            ->assertSee('Halo lounge');
    }

    public function test_authenticated_user_can_post_chat_with_sticker(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->postJson(route('chat.store'), [
            'body' => 'Plot twist gila',
            'sticker' => 'bolt',
        ]);

        $response->assertCreated()
            ->assertJsonPath('message.body', 'Plot twist gila')
            ->assertJsonPath('message.sticker.id', 'bolt');

        $this->assertDatabaseHas('chat_messages', [
            'user_id' => $user->id,
            'body' => 'Plot twist gila',
            'sticker' => 'bolt',
        ]);
    }

    public function test_chat_rejects_empty_payload(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->postJson(route('chat.store'), ['body' => '   '])
            ->assertStatus(422);
    }

    public function test_user_can_upvote_and_switch_to_downvote_chat_message(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $message = ChatMessage::factory()->create();

        $this->actingAs($user)
            ->postJson(route('reactions.toggle', ['type' => 'chat', 'id' => $message->id]), [
                'reaction_type' => 'like',
            ])
            ->assertOk()
            ->assertJsonPath('score', 1)
            ->assertJsonPath('user_vote', 'like');

        $this->actingAs($user)
            ->postJson(route('reactions.toggle', ['type' => 'chat', 'id' => $message->id]), [
                'reaction_type' => 'dislike',
            ])
            ->assertOk()
            ->assertJsonPath('score', -1)
            ->assertJsonPath('user_vote', 'dislike');

        $this->assertDatabaseCount('reactions', 1);
    }

    public function test_emoji_reaction_does_not_replace_a_vote(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $message = ChatMessage::factory()->create();

        $this->actingAs($user)->postJson(route('reactions.toggle', ['type' => 'chat', 'id' => $message->id]), [
            'reaction_type' => 'like',
        ])->assertOk();

        $this->actingAs($user)->postJson(route('reactions.toggle', ['type' => 'chat', 'id' => $message->id]), [
            'emoji' => '🔥',
        ])->assertOk()
            ->assertJsonPath('user_vote', 'like')
            ->assertJsonPath('emojis.0.emoji', '🔥');

        $this->assertDatabaseCount('reactions', 2);
    }

    public function test_chapter_comment_can_include_sticker_and_upvote(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $chapter = Chapter::factory()->create(['status' => 'published']);

        $this->actingAs($user)
            ->post(route('comments.store', $chapter), [
                'content' => 'Bab ini keren',
                'sticker' => 'fire',
            ])
            ->assertRedirect();

        $comment = Comment::query()->firstOrFail();
        $this->assertSame('fire', $comment->sticker);

        $this->actingAs($user)
            ->postJson(route('reactions.toggle', ['type' => 'comment', 'id' => $comment->id]), [
                'reaction_type' => 'like',
            ])
            ->assertOk()
            ->assertJsonPath('score', 1);
    }

    public function test_user_can_submit_review_with_sticker(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $novel = Novel::factory()->create();

        $this->actingAs($user)
            ->post(route('reviews.store', $novel), [
                'rating' => 5,
                'content' => 'Akhir ceritanya 🔥',
                'sticker' => 'fire',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'novel_id' => $novel->id,
            'content' => 'Akhir ceritanya 🔥',
            'sticker' => 'fire',
        ]);
    }

    public function test_review_supports_like_dislike_and_emoji_reactions(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $novel = Novel::factory()->create();
        $review = Review::create([
            'user_id' => User::factory()->create()->id,
            'novel_id' => $novel->id,
            'rating' => 4,
            'content' => 'Review komunitas',
        ]);

        $this->actingAs($user)
            ->postJson(route('reactions.toggle', ['type' => 'review', 'id' => $review->id]), [
                'reaction_type' => 'like',
            ])
            ->assertOk()
            ->assertJsonPath('score', 1)
            ->assertJsonPath('user_vote', 'like');

        $this->actingAs($user)
            ->postJson(route('reactions.toggle', ['type' => 'review', 'id' => $review->id]), [
                'reaction_type' => 'dislike',
            ])
            ->assertOk()
            ->assertJsonPath('score', -1)
            ->assertJsonPath('user_vote', 'dislike');

        $this->actingAs($user)
            ->postJson(route('reactions.toggle', ['type' => 'review', 'id' => $review->id]), [
                'emoji' => '🔥',
            ])
            ->assertOk()
            ->assertJsonPath('user_vote', 'dislike')
            ->assertJsonPath('emojis.0.emoji', '🔥');
    }
}
