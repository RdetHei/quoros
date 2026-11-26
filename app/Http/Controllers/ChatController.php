<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Support\ChatCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $messages = $this->baseQuery()
            ->orderByDesc('id')
            ->limit(80)
            ->get()
            ->reverse()
            ->values();

        return view('chat.index', [
            'messages' => $messages->map(fn (ChatMessage $message) => $this->serialize($message))->all(),
            'packs' => ChatCatalog::packs(),
            'emojis' => ChatCatalog::emojiEntries(),
        ]);
    }

    public function feed(Request $request): JsonResponse
    {
        $afterId = $request->integer('after');

        $messages = $this->baseQuery()
            ->when($afterId > 0, fn ($query) => $query->where('id', '>', $afterId))
            ->when($afterId === 0, fn ($query) => $query->orderByDesc('id')->limit(80))
            ->when($afterId > 0, fn ($query) => $query->orderBy('id')->limit(50))
            ->get();

        if ($afterId === 0) {
            $messages = $messages->reverse()->values();
        }

        return response()->json([
            'messages' => $messages->map(fn (ChatMessage $message) => $this->serialize($message))->all(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'body' => ['nullable', 'string', 'max:1000'],
            'sticker' => ['nullable', 'string', Rule::in(ChatCatalog::stickerIds())],
            'parent_id' => ['nullable', 'integer', 'exists:chat_messages,id'],
        ]);

        $body = trim((string) ($validated['body'] ?? ''));
        $sticker = $validated['sticker'] ?? null;

        if ($body === '' && ! $sticker) {
            return response()->json([
                'message' => 'Tulis pesan, kirim emoji, atau pilih stiker.',
            ], 422);
        }

        $parentId = $validated['parent_id'] ?? null;
        if ($parentId) {
            $parent = ChatMessage::query()->findOrFail($parentId);
            $parentId = $parent->parent_id ?: $parent->id;
        }

        $message = ChatMessage::create([
            'user_id' => Auth::id(),
            'parent_id' => $parentId,
            'body' => $body !== '' ? $body : null,
            'sticker' => $sticker,
        ]);

        $message = $this->baseQuery()->whereKey($message->id)->firstOrFail();

        return response()->json([
            'message' => $this->serialize($message),
        ], 201);
    }

    public function destroy(ChatMessage $message)
    {
        Gate::authorize('delete', $message);

        $message->delete();

        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Pesan dihapus.');
    }

    private function baseQuery()
    {
        $userId = Auth::id();

        return ChatMessage::query()
            ->with([
                'user',
                'parent.user',
                'reactions' => function ($query) use ($userId) {
                    if ($userId) {
                        return;
                    }

                    $query->select(['id', 'user_id', 'reactable_id', 'reactable_type', 'type']);
                },
            ])
            ->withCount(['likes', 'dislikes']);
    }

    /** @return array<string, mixed> */
    private function serialize(ChatMessage $message): array
    {
        $user = $message->user;
        $sticker = $message->stickerMeta();
        $userId = Auth::id();

        return [
            'id' => $message->id,
            'body' => $message->body,
            'body_html' => ChatCatalog::renderText($message->body),
            'sticker' => $sticker,
            'score' => $message->voteScore(),
            'user_vote' => $message->currentUserVote($userId),
            'emojis' => $message->groupedEmojis($userId),
            'created_at' => optional($message->created_at)?->toIso8601String(),
            'created_human' => optional($message->created_at)?->diffForHumans(),
            'can_delete' => $userId && ($userId === $message->user_id || optional(Auth::user())->role === 'admin'),
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username ?? $user->id,
                'role' => $user->role,
                'avatar' => $user->profile_photo_url
                    ?: ($user->profile_photo ? asset('storage/'.$user->profile_photo) : null),
                'initial' => mb_substr($user->name, 0, 1),
                'profile_url' => route('profile.show', $user->username ?? $user->id),
            ] : null,
            'parent' => $message->parent ? [
                'id' => $message->parent->id,
                'name' => $message->parent->user->name ?? 'Pengguna',
                'excerpt' => $message->parent->body
                    ? \Illuminate\Support\Str::limit($message->parent->body, 80)
                    : ($message->parent->stickerMeta()['label'] ?? 'Stiker'),
            ] : null,
        ];
    }
}
