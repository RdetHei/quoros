<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use App\Models\Review;
use App\Services\RecommendationService;
use App\Support\ChatCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function store(Request $request, Novel $novel)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string|max:5000',
            'sticker' => ['nullable', 'string', Rule::in(ChatCatalog::stickerIds())],
        ]);

        $content = trim((string) $request->input('content', ''));
        $sticker = $request->input('sticker');

        if ($content === '' && ! $sticker) {
            return back()->withErrors(['content' => 'Tulis review atau pilih stiker.'])->withInput();
        }

        Review::create([
            'user_id' => Auth::id(),
            'novel_id' => $novel->id,
            'rating' => $request->rating,
            'content' => $content,
            'sticker' => $sticker,
        ]);
        app(RecommendationService::class)->forgetForUser(Auth::id());

        // Recalculate average rating
        $novel->rating_avg = $novel->reviews()->avg('rating') ?: 0;
        $novel->save();

        return back()->with('success', 'Review submitted successfully!');
    }

    public function destroy(Review $review)
    {
        Gate::authorize('delete', $review);

        $novel = $review->novel;
        $review->delete();
        app(RecommendationService::class)->forgetForUser($review->user_id);

        // Recalculate average rating
        $novel->rating_avg = $novel->reviews()->avg('rating') ?: 0;
        $novel->save();

        return back()->with('success', 'Review deleted successfully!');
    }
}
