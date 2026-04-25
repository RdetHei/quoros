<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use App\Models\ReadingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Stats Ringkas
        // Simulasi jam baca (karena belum ada tracking di DB)
        $totalReadingHours = $user->readingHistories()->count() * 0.5; // Anggap 1 chapter = 30 menit
        $favoriteNovel = $user->bookmarks()->first()?->novel;
        $userPoints = 1250; // Simulasi poin/koin

        // 2. Lanjutkan Membaca (Hero Section)
        $lastRead = $user->readingHistories()
            ->with(['novel', 'chapter'])
            ->latest()
            ->first();

        if ($lastRead) {
            $totalChapters = $lastRead->novel->chapters()->count();
            // Hitung posisi chapter saat ini (berdasarkan ID)
            $currentChapterPos = $lastRead->novel->chapters()
                ->where('id', '<=', $lastRead->chapter_id)
                ->count();
            
            $lastRead->progress = $totalChapters > 0 ? round(($currentChapterPos / $totalChapters) * 100) : 0;
            $lastRead->current_pos = $currentChapterPos;
            $lastRead->total_chapters = $totalChapters;
        }

        // 3. Statistik Penulis (Jika Role Writer/Admin)
        $writerStats = null;
        if ($user->role === 'writer' || $user->role === 'admin') {
            $myNovels = $user->novels;
            $writerStats = [
                'total_views' => $myNovels->sum('view_count'),
                'total_comments' => $myNovels->map(fn($n) => $n->chapters->sum(fn($c) => $c->comments->count()))->sum(),
                'avg_rating' => $myNovels->avg('rating_avg') ?? 0,
                'novel_count' => $myNovels->count()
            ];
        }

        // 4. Tabbed Content
        $bookmarks = $user->bookmarks()->with('novel.author')->latest()->get();
        $histories = $user->readingHistories()->with(['novel', 'chapter'])->latest()->take(10)->get();
        $recommendations = Novel::whereNotIn('id', $bookmarks->pluck('novel_id'))
            ->orderBy('rating_avg', 'desc')
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'user', 
            'totalReadingHours', 
            'favoriteNovel', 
            'userPoints',
            'lastRead',
            'writerStats',
            'bookmarks',
            'histories',
            'recommendations'
        ));
    }
}
