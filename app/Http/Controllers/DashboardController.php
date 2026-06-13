<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Novel;
use App\Models\NovelRequest;
use App\Models\ReadingHistory;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use App\Models\Announcement;
use App\Services\CloudinaryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function index(Request $request)
    {
        if ($request->get('tab') === 'settings') {
            return redirect()->route('settings');
        }

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return match ($user->role) {
            'writer' => $this->writerDashboard($user),
            default => $this->readerDashboard($user),
        };
    }

    public function settings()
    {
        return redirect()->route('settings.v2');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['nullable', 'string', 'alpha_dash', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'bio' => 'nullable|string|max:500',
            'is_public_reading_list' => 'nullable|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'bio']);
        $data['username'] = $request->filled('username') ? $request->username : null;
        $data['is_public_reading_list'] = $request->boolean('is_public_reading_list');

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_public_id) {
                $this->cloudinaryService->deleteImage($user->profile_photo_public_id);
            }
            $result = $this->cloudinaryService->uploadProfile($request->file('profile_photo'));
            $data['profile_photo_url'] = $result['url'];
            $data['profile_photo_public_id'] = $result['public_id'];
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function becomeWriter(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'user') {
            return back()->with('error', 'You already have contributor access.');
        }

        // Update role to writer
        $user->role = 'writer';
        $user->save();

        return back()->with('success', 'Congratulations! Your account has been successfully changed to Writer. You can now start creating your own novels.');
    }

    private function writerDashboard(User $user)
    {
        $request = request();
        $allNovels = $user->novels()->select('id', 'title', 'view_count')->get();
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

        $todayStart = Carbon::today();

        // 1. Overview KPIs (Aggregated for all novels)
        $viewsToday = DB::table('novel_view_logs')
            ->whereIn('novel_id', $allNovels->pluck('id'))
            ->whereDate('viewed_on', '>=', $todayStart)
            ->sum('views');

        $newBookmarksToday = Bookmark::whereIn('novel_id', $allNovels->pluck('id'))
            ->whereDate('created_at', '>=', $todayStart)
            ->count();

        $averageRating = (float) Review::whereIn('novel_id', $allNovels->pluck('id'))->avg('rating');

        // 2. Library / Catalog
        $myNovels = Novel::where('author_id', $user->id)
            ->withCount(['chapters', 'bookmarks'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        // 3. Analytics (Last 30 Days - Filtered)
        $days = 30;
        $startDate = Carbon::now()->subDays($days);

        $bookmarksDaily = Bookmark::whereIn('novel_id', $novelIds)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date');

        $reviewsDaily = Review::whereIn('novel_id', $novelIds)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date');

        $readersDaily = DB::table('novel_view_logs')
            ->whereIn('novel_id', $novelIds)
            ->where('viewed_on', '>=', $startDate->toDateString())
            ->select('viewed_on as date', DB::raw('SUM(views) as count'))
            ->groupBy('viewed_on')
            ->pluck('count', 'date');

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

        $totalBookmarks = Bookmark::whereIn('novel_id', $novelIds)->count();
        $totalReviews = Review::whereIn('novel_id', $novelIds)->count();

        // 4. Community (Reviews & Comments - Unfiltered for overview)
        $latestReviews = Review::with(['user:id,name', 'novel:id,title,slug'])
            ->whereIn('novel_id', $allNovels->pluck('id'))
            ->latest()
            ->take(10)
            ->get();

        $latestComments = Comment::with([
            'user:id,name',
            'chapter:id,novel_id,title',
            'chapter.novel:id,title,slug',
        ])
            ->whereHas('chapter', fn ($query) => $query->whereIn('novel_id', $allNovels->pluck('id')))
            ->latest()
            ->take(10)
            ->get();

        // 5. Drafts & Work in Progress
        $draftChapters = Chapter::with('novel:id,title,slug')
            ->whereHas('novel', fn ($query) => $query->where('author_id', $user->id))
            ->where(function ($query) {
                $query->where('status', 'draft')
                    ->orWhere('published_at', '>', now());
            })
            ->latest()
            ->take(6)
            ->get();

        $writerTips = Announcement::where('is_active', true)
            ->where(function ($query) {
                $query->where('title', 'like', '%writer%')
                    ->orWhere('content', 'like', '%writer%');
            })
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard.writer', compact(
            'user',
            'viewsToday',
            'newBookmarksToday',
            'averageRating',
            'latestReviews',
            'latestComments',
            'draftChapters',
            'myNovels',
            'writerTips',
            'labels',
            'bookmarkData',
            'reviewData',
            'readerData',
            'totalViews',
            'totalBookmarks',
            'totalReviews',
            'allNovels',
            'selectedNovelId'
        ));
    }

    private function readerDashboard(User $user)
    {
        $totalReadingHours = round($user->readingHistories()->count() * 0.5, 1);

        $lastRead = $user->readingHistories()
            ->whereHas('novel')
            ->whereHas('chapter')
            ->with(['novel', 'chapter'])
            ->latest()
            ->first();

        if ($lastRead && $lastRead->novel) {
            $totalChapters = $lastRead->novel->chapters()->count();
            $currentChapterPos = $lastRead->novel->chapters()
                ->where('id', '<=', $lastRead->chapter_id)
                ->count();

            $lastRead->progress = $totalChapters > 0 ? round(($currentChapterPos / $totalChapters) * 100) : 0;
        }

        $bookmarks = $user->bookmarks()
            ->whereHas('novel')
            ->with('novel.author')
            ->latest()
            ->take(6)
            ->get();

        $dailyGoalMinutes = 60;
        $todayReads = ReadingHistory::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $todayMinutes = min($dailyGoalMinutes, $todayReads * 15);
        $dailyGoalProgress = (int) round(($todayMinutes / $dailyGoalMinutes) * 100);

        return view('dashboard.reader', compact(
            'user',
            'totalReadingHours',
            'lastRead',
            'bookmarks',
            'dailyGoalMinutes',
            'todayMinutes',
            'dailyGoalProgress'
        ));
    }
}
