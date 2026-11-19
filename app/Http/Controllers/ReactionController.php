<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Review;
use App\Support\ChatCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function toggle(Request $request, string $type, int $id)
    {
        $request->validate([
            'reaction_type' => ['nullable', 'in:like,dislike'],
            'emoji' => ['nullable', 'string', 'max:16'],
        ]);

        if (! $request->filled('reaction_type') && ! $request->filled('emoji')) {
            return $this->respond($request, 'Pilih vote atau emoji.', 422, error: true);
        }

        $modelMap = [
            'comment' => Comment::class,
            'chat' => ChatMessage::class,
            'review' => Review::class,
        ];

        if (! isset($modelMap[$type])) {
            return $this->respond($request, 'Jenis reaksi tidak valid.', 422, error: true);
        }

        $modelClass = $modelMap[$type];
        $model = $modelClass::query()->findOrFail($id);
        $userId = Auth::id();

        if ($request->filled('emoji')) {
            $emoji = $request->string('emoji')->toString();

            if (! ChatCatalog::isAllowedEmoji($emoji)) {
                return $this->respond($request, 'Emoji tidak tersedia.', 422, error: true);
            }

            $reactionType = 'e:'.$emoji;
            $existing = Reaction::query()
                ->where('user_id', $userId)
                ->where('reactable_id', $id)
                ->where('reactable_type', $modelClass)
                ->where('type', $reactionType)
                ->first();

            if ($existing) {
                $existing->delete();
            } else {
                Reaction::create([
                    'user_id' => $userId,
                    'reactable_id' => $id,
                    'reactable_type' => $modelClass,
                    'type' => $reactionType,
                ]);
            }
        } else {
            $reactionType = $request->string('reaction_type')->toString();
            $existing = Reaction::query()
                ->where('user_id', $userId)
                ->where('reactable_id', $id)
                ->where('reactable_type', $modelClass)
                ->whereIn('type', ['like', 'dislike'])
                ->first();

            if ($existing) {
                if ($existing->type === $reactionType) {
                    $existing->delete();
                } else {
                    $existing->update(['type' => $reactionType]);
                }
            } else {
                Reaction::create([
                    'user_id' => $userId,
                    'reactable_id' => $id,
                    'reactable_type' => $modelClass,
                    'type' => $reactionType,
                ]);
            }
        }

        $model->unsetRelation('reactions');
        $model->load('reactions');
        $model->loadCount(['likes', 'dislikes']);

        $payload = [
            'score' => $model->voteScore(),
            'user_vote' => $model->currentUserVote($userId),
            'emojis' => $model->groupedEmojis($userId),
            'likes_count' => $model->likes_count,
            'dislikes_count' => $model->dislikes_count,
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($payload);
        }

        return back();
    }

    private function respond(Request $request, string $message, int $status = 200, bool $error = false)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => $message], $status);
        }

        return back()->with($error ? 'error' : 'success', $message);
    }
}
