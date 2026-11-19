<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Comment;
use App\Services\InAppNotificationService;
use App\Support\ChatCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function __construct(
        private InAppNotificationService $notifications,
    ) {}

    public function store(Request $request, Chapter $chapter)
    {
        $request->validate([
            'content' => ['nullable', 'string', 'max:5000'],
            'sticker' => ['nullable', 'string', Rule::in(ChatCatalog::stickerIds())],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ]);

        $content = trim((string) $request->input('content', ''));
        $sticker = $request->input('sticker');

        if ($content === '' && ! $sticker) {
            return back()
                ->withErrors(['content' => 'Tulis komentar atau pilih stiker.'])
                ->withInput();
        }

        $parentId = $request->parent_id;

        if ($parentId) {
            $parent = Comment::query()
                ->where('id', $parentId)
                ->where('chapter_id', $chapter->id)
                ->whereNull('parent_id')
                ->firstOrFail();
            $parentId = $parent->id;
        }

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'chapter_id' => $chapter->id,
            'parent_id' => $parentId,
            'content' => $content !== '' ? $content : '',
            'sticker' => $sticker,
        ]);

        if ($parentId) {
            $this->notifications->notifyCommentReply($comment);
        }

        return back()->with('success', $parentId ? 'Balasan terkirim.' : 'Komentar ditambahkan.');
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        DB::transaction(fn () => $comment->delete());

        return back()->with('success', 'Komentar dihapus.');
    }
}
