<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Genre;
use App\Models\Novel;
use App\Models\ReadingHistory;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim($request->query('q', ''));
        $genre = $request->query('genre');
        $filterStatus = $request->query('filter_status');
        $tab = $request->query('tab');
        if (!in_array($tab, ['all', 'reading', 'plan', 'completed'], true)) {
            $tab = 'all';
        }
        $sort = $request->query('sort', 'recently_read');
        if (!in_array($sort, ['recently_read', 'recently_saved', 'title', 'progress_desc', 'rating_desc'], true)) {
            $sort = 'recently_read';
        }

        $baseQuery = $user->bookmarks()
            ->whereHas('novel')
            ->with([
                'novel.author',
                'novel.genres:id,name,slug',
                'novel.tags:id,name,slug',
            ])
            ->withCount('novel as total_chapters')
            ->latest('bookmarks.updated_at');

        if ($search) {
            $baseQuery->whereHas('novel', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('alternative_title', 'like', "%{$search}%")
                    ->orWhereHas('author', function ($qa) use ($search) {
                        $qa->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($genre) {
            $baseQuery->whereHas('novel.genres', function ($qg) use ($genre) {
                $qg->where('slug', $genre);
            });
        }

        if ($filterStatus) {
            $baseQuery->whereHas('novel', function ($q) use ($filterStatus) {
                $q->where('status', $filterStatus);
            });
        }

        $bookmarkIdsWithStatus = [];
        $tempItems = $baseQuery->get();
        $readingList = [];
        $planList = [];
        $completedList = [];
        $lastReadAt = [];
        $savedAt = [];

        foreach ($tempItems as $bm) {
            $lastRead = ReadingHistory::where('user_id', $user->id)
                ->where('novel_id', $bm->novel_id)
                ->latest()
                ->first();

            $readChaptersCount = ReadingHistory::where('user_id', $user->id)
                ->where('novel_id', $bm->novel_id)
                ->distinct('chapter_id')
                ->count('chapter_id');

            $totalChapters = (int) ($bm->total_chapters ?? Chapter::where('novel_id', $bm->novel_id)->count());
            $progress = $totalChapters > 0 ? min(($readChaptersCount / $totalChapters) * 100, 100) : 0;
            $novelStatus = (string) ($bm->novel->status ?? '');

            if ($progress >= 100 || $novelStatus === 'completed' && $progress >= 95) {
                $readingStatus = 'completed';
            } elseif ($readChaptersCount > 0) {
                $readingStatus = 'reading';
            } else {
                $readingStatus = 'plan';
            }

            $bookmarkIdsWithStatus[$bm->id] = [
                'status' => $readingStatus,
                'progress' => $progress,
                'read_count' => $readChaptersCount,
                'total' => $totalChapters,
                'last_read_chapter' => $lastRead ? $lastRead->chapter : null,
                'last_read_at' => $lastRead ? $lastRead->created_at : null,
                'saved_at' => $bm->created_at,
                'novel_created_at' => $bm->novel?->created_at ?? $bm->created_at,
                'rating_avg' => (float) ($bm->novel?->rating_avg ?? 0),
                'new_chapters' => $lastRead ? Chapter::where('novel_id', $bm->novel_id)
                    ->where('created_at', '>', $lastRead->created_at)
                    ->count() : Chapter::where('novel_id', $bm->novel_id)->count(),
            ];

            $lastReadAt[$bm->id] = $lastRead ? $lastRead->created_at->timestamp : 0;
            $savedAt[$bm->id] = $bm->created_at->timestamp;

            if ($readingStatus === 'reading') $readingList[] = $bm->id;
            elseif ($readingStatus === 'plan') $planList[] = $bm->id;
            else $completedList[] = $bm->id;
        }

        $filteredIds = match ($tab) {
            'reading' => $readingList,
            'plan' => $planList,
            'completed' => $completedList,
            default => array_keys($bookmarkIdsWithStatus),
        };

        $counts = [
            'all' => count($bookmarkIdsWithStatus),
            'reading' => count($readingList),
            'plan' => count($planList),
            'completed' => count($completedList),
        ];

        $sortArr = $filteredIds;
        switch ($sort) {
            case 'recently_saved':
                uasort($sortArr, fn($a, $b) => ($savedAt[$b] ?? 0) <=> ($savedAt[$a] ?? 0));
                break;
            case 'title':
                $titleById = [];
                foreach ($tempItems as $tt) $titleById[$tt->id] = mb_strtolower($tt->novel?->title ?? '');
                uasort($sortArr, fn($a, $b) => strcmp($titleById[$a] ?? '', $titleById[$b] ?? ''));
                break;
            case 'progress_desc':
                uasort($sortArr, fn($a, $b) => ($bookmarkIdsWithStatus[$b]['progress'] ?? 0) <=> ($bookmarkIdsWithStatus[$a]['progress'] ?? 0));
                break;
            case 'rating_desc':
                uasort($sortArr, fn($a, $b) => ($bookmarkIdsWithStatus[$b]['rating_avg'] ?? 0) <=> ($bookmarkIdsWithStatus[$a]['rating_avg'] ?? 0));
                break;
            default:
                uasort($sortArr, fn($a, $b) => ($lastReadAt[$b] ?? 0) <=> ($lastReadAt[$a] ?? 0));
                break;
        }
        $filteredOrderIds = array_values($sortArr);

        $rawCollection = $tempItems->keyBy('id');

        $sortedBookmarks = collect($filteredOrderIds)
            ->map(fn($id) => $rawCollection->get($id))
            ->filter()
            ->values();

        $enriched = $sortedBookmarks->map(function ($bm) use ($bookmarkIdsWithStatus) {
            $meta = $bookmarkIdsWithStatus[$bm->id] ?? null;
            if (!$meta) return null;
            $bm->reading_status = $meta['status'];
            $bm->read_chapters_count = (int) $meta['read_count'];
            $bm->total_chapters = (int) $meta['total'];
            $bm->progress_percentage = (float) $meta['progress'];
            $bm->last_read_chapter = $meta['last_read_chapter'];
            $bm->last_read_at = $meta['last_read_at'];
            $bm->new_chapters_count = (int) ($meta['new_chapters'] ?? 0);
            return $bm;
        })->filter();

        $perPage = 16;
        $page = (int) $request->query('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $enriched->forPage($page, $perPage)->values(),
            $enriched->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
        $paginated->withPath($request->url());

        $genres = Genre::whereHas('novels.bookmarks', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->orderBy('name')->get();

        $statuses = [
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'hiatus' => 'Hiatus',
            'one_shot' => 'One Shot',
        ];

        $sortOptions = [
            'recently_read' => 'Recently Read',
            'recently_saved' => 'Recently Saved',
            'progress_desc' => 'Highest Progress',
            'rating_desc' => 'Highest Rating',
            'title' => 'Title A–Z',
        ];

        return view('user.bookmarks', compact(
            'paginated',
            'counts',
            'tab',
            'search',
            'genre',
            'filterStatus',
            'sort',
            'genres',
            'statuses',
            'sortOptions',
        ));
    }

    public function toggle(Novel $novel)
    {
        $user = Auth::user();

        $bookmark = Bookmark::where('user_id', $user->id)
            ->where('novel_id', $novel->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            app(RecommendationService::class)->forgetForUser($user->id);

            if (request()->ajax()) {
                return response()->json([
                    'status' => 'removed',
                    'message' => 'Novel removed from bookmarks.'
                ]);
            }

            return back()->with('success', 'Novel removed from bookmarks.');
        }

        Bookmark::create([
            'user_id' => $user->id,
            'novel_id' => $novel->id,
        ]);
        app(RecommendationService::class)->forgetForUser($user->id);

        if (request()->ajax()) {
            return response()->json([
                'status' => 'added',
                'message' => 'Novel added to bookmarks.'
            ]);
        }

        return back()->with('success', 'Novel added to bookmarks.');
    }
}
