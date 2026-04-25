<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Novel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Auth::user()->bookmarks()
            ->with(['novel.author', 'novel.genres'])
            ->latest()
            ->paginate(18);

        return view('user.bookmarks', compact('bookmarks'));
    }

    public function toggle(Novel $novel)
    {
        $user = Auth::user();
        
        $bookmark = Bookmark::where('user_id', $user->id)
                            ->where('novel_id', $novel->id)
                            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return back()->with('success', 'Novel dihapus dari bookmark.');
        }

        Bookmark::create([
            'user_id' => $user->id,
            'novel_id' => $novel->id,
        ]);

        return back()->with('success', 'Novel ditambahkan ke bookmark.');
    }
}
