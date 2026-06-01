<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Comment;
use App\Services\InAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function __construct(
        private InAppNotificationService $notifications,
    ) {}

    public function store(Request $request, Chapter $chapter)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:5000'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ]);

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
            'content' => $request->content,
        ]);

        if ($parentId) {
            $this->notifications->notifyCommentReply($comment);
        }

        return back()->with('success', $parentId ? 'Reply sent.' : 'Comment added.');
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
