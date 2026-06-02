<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function analyticsPro()
    {
        return redirect()->route('writer.stats')
            ->with('success', 'Analytics Pro currently uses the Writer Stats engine.');
    }

    public function feedbackHub()
    {
        $user = Auth::user();
        $novelIds = $user->novels()->pluck('id');

        $reviews = Review::with(['user:id,name', 'novel:id,title,slug'])
            ->whereIn('novel_id', $novelIds)
            ->latest()
            ->paginate(15, ['*'], 'review_page');

        $comments = Comment::with([
            'user:id,name',
            'chapter:id,novel_id,title',
            'chapter.novel:id,title,slug',
        ])
            ->whereHas('chapter', fn ($query) => $query->whereIn('novel_id', $novelIds))
            ->latest()
            ->paginate(15, ['*'], 'comment_page');

        return view('writer.feedback-hub.index', compact('reviews', 'comments'));
    }
}
