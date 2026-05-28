<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Review;
use App\Models\ReadingHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $allNovels = $user->novels()->select('id', 'title')->get();
        
        $selectedNovelId = $request->get('novel_id');
        
        if ($selectedNovelId && $allNovels->contains('id', $selectedNovelId)) {
            $novelIds = [$selectedNovelId];
            $selectedNovel = $allNovels->firstWhere('id', $selectedNovelId);
            $totalViews = $selectedNovel->view_count ?? 0;
        } else {
            $novelIds = $allNovels->pluck('id');
            $selectedNovelId = null;
            $totalViews = $user->novels()->sum('view_count');
        }

        // 1. Overall Stats
        $totalBookmarks = Bookmark::whereIn('novel_id', $novelIds)->count();
        $totalReviews = Review::whereIn('novel_id', $novelIds)->count();

        // 2. Data for Charts (Last 30 Days)
        $days = 30;
        $startDate = Carbon::now()->subDays($days);

        // Bookmark Growth
        $bookmarksDaily = Bookmark::whereIn('novel_id', $novelIds)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date');

        // Review Growth
        $reviewsDaily = Review::whereIn('novel_id', $novelIds)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date');

        // Reading History (Proxy for Daily Readers)
        $readersDaily = ReadingHistory::whereIn('novel_id', $novelIds)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date');

        // Prepare full 30-day range to avoid gaps in chart
        $labels = [];
        $bookmarkData = [];
        $reviewData = [];
        $readerData = [];

        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('d M');
            $bookmarkData[] = $bookmarksDaily->get($date, 0);
            $reviewData[] = $reviewsDaily->get($date, 0);
            $readerData[] = $readersDaily->get($date, 0);
        }

        return view('writer.stats', compact(
            'totalViews', 'totalBookmarks', 'totalReviews',
            'labels', 'bookmarkData', 'reviewData', 'readerData',
            'allNovels', 'selectedNovelId'
        ));
    }
}
