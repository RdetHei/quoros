<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Novel $novel)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'novel_id' => $novel->id,
            'rating' => $request->rating,
            'content' => $request->content,
        ]);

        // Recalculate average rating
        $novel->rating_avg = $novel->reviews()->avg('rating') ?: 0;
        $novel->save();

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }

    public function destroy(Review $review)
    {
        if (Auth::user()->role !== 'admin' && $review->user_id !== Auth::id()) {
            abort(403);
        }

        $novel = $review->novel;
        $review->delete();

        // Recalculate average rating
        $novel->rating_avg = $novel->reviews()->avg('rating') ?: 0;
        $novel->save();

        return back()->with('success', 'Ulasan berhasil dihapus!');
    }
}
