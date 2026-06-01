<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Novel;
use App\Models\ReadingHistory;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookmarks = $user->bookmarks()
            ->whereHas('novel')
            ->with(['novel.author', 'novel.genres'])
            ->withCount('novel as total_chapters')
            ->latest()
            ->paginate(18)
            ->withQueryString();

        // Menambahkan progress membaca untuk setiap bookmark
        foreach ($bookmarks as $bookmark) {
            $lastRead = ReadingHistory::where('user_id', $user->id)
                ->where('novel_id', $bookmark->novel_id)
                ->with('chapter')
                ->latest()
                ->first();

            $totalChapters = Chapter::where('novel_id', $bookmark->novel_id)->count();

            // Mencari urutan bab yang dibaca (berdasarkan ID atau urutan waktu)
            // Di sini kita asumsikan progress berdasarkan jumlah bab unik yang pernah dibaca oleh user untuk novel tersebut
            $readChaptersCount = ReadingHistory::where('user_id', $user->id)
                ->where('novel_id', $bookmark->novel_id)
                ->distinct('chapter_id')
                ->count('chapter_id');

            $bookmark->read_chapters_count = $readChaptersCount;
            $bookmark->total_chapters = $totalChapters;
            $bookmark->progress_percentage = $totalChapters > 0 ? min(($readChaptersCount / $totalChapters) * 100, 100) : 0;
            $bookmark->last_read_chapter = $lastRead ? $lastRead->chapter : null;
        }

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

            if (request()->ajax()) {
                return response()->json([
                    'status' => 'removed',
                    'message' => 'Novel dihapus dari bookmark.'
                ]);
            }

            return back()->with('success', 'Novel dihapus dari bookmark.');
        }

        Bookmark::create([
            'user_id' => $user->id,
            'novel_id' => $novel->id,
        ]);

        if (request()->ajax()) {
            return response()->json([
                'status' => 'added',
                'message' => 'Novel ditambahkan ke bookmark.'
            ]);
        }

        return back()->with('success', 'Novel ditambahkan ke bookmark.');
    }
}
