<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Services\CloudinaryService;
use App\Services\RecommendationService;
use App\Services\NovelViewService;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Genre;
use App\Models\Novel;
use App\Models\NovelRequest;
use App\Models\ReadingHistory;
use App\Models\NovelViewLog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class NovelController extends Controller
{
    protected $cloudinaryService;

    public function __construct(
        CloudinaryService $cloudinaryService,
        private RecommendationService $recommendations,
        private NovelViewService $novelViews,
    ) {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function landing()
    {
        $featuredQuery = Novel::with(['author', 'genres', 'chapters' => function ($q) {
                $q->published()->orderBy('order')->orderBy('id')->take(1);
            }])
            ->withCount('chapters')
            ->withAvg('reviews', 'rating')
            ->where('is_featured', true)
            ->take(5);

        if (Auth::check()) {
            $featuredQuery->withExists(['bookmarks as is_bookmarked' => function ($q) {
                $q->where('user_id', Auth::id());
            }]);
        }

        $featuredNovels = $featuredQuery->get();

        if ($featuredNovels->isEmpty()) {
            $fallbackQuery = Novel::with(['author', 'genres', 'chapters' => function ($q) {
                    $q->published()->orderBy('order')->orderBy('id')->take(1);
                }])
                ->withCount('chapters')
                ->withAvg('reviews', 'rating')
                ->orderByDesc('view_count')
                ->take(5);

            if (Auth::check()) {
                $fallbackQuery->withExists(['bookmarks as is_bookmarked' => function ($q) {
                    $q->where('user_id', Auth::id());
                }]);
            }
            $featuredNovels = $fallbackQuery->get();
        }

        $projectUpdates = Novel::with(['author', 'genres', 'chapters' => function ($q) {
                $q->published()->latest()->take(1);
            }])
            ->withCount('chapters')
            ->whereHas('chapters')
            ->withMax('chapters', 'created_at')
            ->orderByDesc('chapters_max_created_at')
            ->take(15)
            ->get();

        $popularGenres = Genre::withCount('novels')
            ->orderByDesc('novels_count')
            ->orderBy('name')
            ->take(16)
            ->get()
            ->filter(fn ($genre) => $genre->novels_count > 0);

        $popularTags = Tag::withCount('novels')
            ->orderByDesc('novels_count')
            ->orderBy('name')
            ->take(16)
            ->get()
            ->filter(fn ($tag) => $tag->novels_count > 0);

        $monthlyViewed = $this->novelViews->trending(30, 14);
        if ($monthlyViewed->isEmpty()) {
            $monthlyViewed = Novel::with(['author', 'genres', 'chapters' => function ($q) {
                    $q->published()->latest()->take(1);
                }])
                ->withCount('chapters')
                ->orderByDesc('view_count')
                ->take(14)
                ->get();
        } else {
            $monthlyViewed->load(['chapters' => function ($q) {
                $q->published()->latest()->take(1);
            }]);
        }

        $hotTake = $featuredNovels->skip(1)->first()
            ?? $projectUpdates->first()
            ?? Novel::with(['author', 'genres', 'chapters' => function ($q) {
                $q->published()->orderBy('order')->orderBy('id')->take(1);
            }])->withCount('chapters')->orderByDesc('view_count')->first();

        $forYou = Auth::check()
            ? $this->recommendations->forUser(Auth::user(), 4)
            : $this->recommendations->forGuest(4);

        if ($forYou->isEmpty()) {
            $forYou = $this->recommendations->forGuest(4);
        }

        if ($forYou->isEmpty()) {
            $forYou = Novel::with(['author', 'genres', 'chapters' => function ($q) {
                    $q->published()->latest()->take(3);
                }])
                ->withCount('chapters')
                ->whereHas('chapters')
                ->withMax('chapters', 'created_at')
                ->orderByDesc('chapters_max_created_at')
                ->take(4)
                ->get();
        }

        $marathonNovel = $projectUpdates->skip(2)->first() ?? $featuredNovels->first();

        return view('welcome', compact(
            'featuredNovels',
            'projectUpdates',
            'popularGenres',
            'popularTags',
            'monthlyViewed',
            'hotTake',
            'forYou',
            'marathonNovel',
        ));
    }

    public function index(Request $request)
    {
        $query = Novel::with(['author', 'genres'])
            ->withCount('chapters');

        if ($request->genre) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('slug', $request->genre);
            });
        }

        if ($request->search) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        $novels = $query->latest()->paginate(12)->withQueryString();

        // Featured Novels for Carousel (Selected by Admin)
        $featuredQuery = Novel::with(['author', 'genres'])
            ->withCount('chapters')
            ->where('is_featured', true)
            ->take(5);

        if (Auth::check()) {
            $featuredQuery->withExists(['bookmarks as is_bookmarked' => function($q) {
                $q->where('user_id', Auth::id());
            }]);
        }

        $featuredNovels = $featuredQuery->get();

        // Fallback to top viewed if no featured novels selected
        if ($featuredNovels->isEmpty()) {
            $fallbackQuery = Novel::with(['author', 'genres'])
                ->withCount('chapters')
                ->orderByDesc('view_count')
                ->take(5);

            if (Auth::check()) {
                $fallbackQuery->withExists(['bookmarks as is_bookmarked' => function($q) {
                    $q->where('user_id', Auth::id());
                }]);
            }
            $featuredNovels = $fallbackQuery->get();
        }

        // Recently Updated: Novels with the most recent chapters
        $recentlyUpdated = Novel::with(['author', 'genres', 'chapters' => function($q) {
                $q->published()->latest()->take(3);
            }])
            ->withCount('chapters')
            ->whereHas('chapters')
            ->withMax('chapters', 'created_at')
            ->orderByDesc('chapters_max_created_at')
            ->take(6)
            ->get();

        $genres = Genre::withCount('novels')->orderBy('name')->get();

        $popularTags = Tag::withCount('novels')
            ->orderByDesc('novels_count')
            ->orderBy('name')
            ->get()
            ->filter(fn ($tag) => $tag->novels_count > 0)
            ->take(32);

        $weeklyTop = $this->novelViews->trending(7, 10);
        $monthlyTop = $this->novelViews->trending(30, 10);

        // New nominations
        $topRated = Novel::with(['author', 'genres'])
            ->withCount('chapters')
            ->orderByDesc('rating_avg')
            ->take(10)
            ->get();

        $mostBookmarked = Novel::with(['author', 'genres'])
            ->withCount('chapters')
            ->withCount('bookmarks')
            ->orderByDesc('bookmarks_count')
            ->take(10)
            ->get();

        $popularNovels = Novel::with(['author', 'genres'])
            ->withCount('chapters')
            ->orderByDesc('view_count')
            ->take(6)
            ->get();

        return view('novels.index', compact(
            'novels',
            'genres',
            'popularTags',
            'recentlyUpdated',
            'weeklyTop',
            'monthlyTop',
            'featuredNovels',
            'topRated',
            'mostBookmarked',
            'popularNovels',
        ));
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $genresRaw = $request->input('genres', []);
        if (!is_array($genresRaw)) {
            $singleGenre = $request->get('genre');
            $genresRaw = $singleGenre ? [$singleGenre] : [];
        }
        $genreSlugs = array_values(array_filter(array_map('strval', $genresRaw)));

        $status = $request->get('status');
        $type = $request->get('type');
        $region = $request->get('region');

        $tagsRaw = $request->input('tags', []);
        if (!is_array($tagsRaw)) {
            $singleTag = $request->get('tag');
            $tagsRaw = $singleTag ? [$singleTag] : [];
        }
        $tagSlugs = array_values(array_filter(array_map('strval', $tagsRaw)));

        $minRating = $request->get('min_rating');
        $sort = $request->get('sort', 'latest');

        $query = Novel::with(['author', 'genres', 'tags']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('alternative_title', 'like', "%{$search}%")
                    ->orWhereHas('author', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($genreSlugs)) {
            $query->whereHas('genres', function ($q) use ($genreSlugs) {
                $q->whereIn('slug', $genreSlugs);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($region) {
            $query->where('region', $region);
        }

        if (!empty($tagSlugs)) {
            $query->whereHas('tags', function ($q) use ($tagSlugs) {
                $q->whereIn('slug', $tagSlugs);
            });
        }

        if ($minRating !== null && $minRating !== '') {
            $query->where('rating_avg', '>=', (float) $minRating);
        }

        switch ($sort) {
            case 'rating':
                $query->orderByDesc('rating_avg');
                break;
            case 'views':
                $query->orderByDesc('view_count');
                break;
            case 'trending':
                $since = now()->subDays(7)->toDateString();
                $query->orderByDesc(
                    NovelViewLog::query()
                        ->selectRaw('COALESCE(SUM(views), 0)')
                        ->whereColumn('novel_view_logs.novel_id', 'novels.id')
                        ->where('viewed_on', '>=', $since),
                );
                break;
            case 'title':
                $query->orderBy('title');
                break;
            default:
                $query->latest();
                break;
        }

        $novels = $query->paginate(20)->withQueryString();
        $genres = Genre::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        $statuses = [
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'hiatus' => 'Hiatus',
            'one_shot' => 'One Shot',
        ];

        $types = [
            'original' => 'Original',
            'web_novel' => 'Web Novel',
            'light_novel' => 'Light Novel',
        ];

        $regions = Novel::whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region')
            ->values()
            ->all();

        $regionList = array_combine(array_map('strval', $regions), $regions);

        $ratingOptions = [
            '4.5' => '≥ 4.5 ★',
            '4' => '≥ 4.0 ★',
            '3.5' => '≥ 3.5 ★',
            '3' => '≥ 3.0 ★',
        ];

        $sortOptions = [
            'latest' => 'Newest First',
            'rating' => 'Highest Rating',
            'views' => 'Most Viewed',
            'trending' => 'Trending (7D)',
            'title' => 'Title A–Z',
            'chapters' => 'Most Chapters',
        ];

        $selectedGenreNames = [];
        foreach ($genres as $g) {
            if (in_array($g->slug, $genreSlugs, true)) {
                $selectedGenreNames[$g->slug] = $g->name;
            }
        }
        $selectedTagNames = [];
        foreach ($tags as $t) {
            if (in_array($t->slug, $tagSlugs, true)) {
                $selectedTagNames[$t->slug] = $t->name;
            }
        }

        $activeFilters = [];
        foreach ($selectedGenreNames as $slug => $name) {
            $activeFilters[] = ['group' => 'genres', 'value' => $slug, 'label' => strtoupper($name), 'kind' => 'genre'];
        }
        foreach ($selectedTagNames as $slug => $name) {
            $activeFilters[] = ['group' => 'tags', 'value' => $slug, 'label' => strtoupper($name), 'kind' => 'tag'];
        }
        if ($status && isset($statuses[$status])) {
            $activeFilters[] = ['group' => 'status', 'value' => $status, 'label' => strtoupper($statuses[$status]), 'kind' => 'status'];
        }
        if ($region) {
            $activeFilters[] = ['group' => 'region', 'value' => $region, 'label' => strtoupper($region), 'kind' => 'region'];
        }
        if ($type && isset($types[$type])) {
            $activeFilters[] = ['group' => 'type', 'value' => $type, 'label' => strtoupper($types[$type]), 'kind' => 'type'];
        }
        if ($minRating !== null && $minRating !== '' && isset($ratingOptions[(string)$minRating])) {
            $activeFilters[] = ['group' => 'min_rating', 'value' => $minRating, 'label' => strtoupper($ratingOptions[(string)$minRating]), 'kind' => 'rating'];
        }

        return view('novels.search', compact(
            'novels', 'search', 'genres', 'tags', 'minRating',
            'genreSlugs', 'tagSlugs', 'status', 'type', 'region', 'sort',
            'statuses', 'types', 'regionList', 'ratingOptions', 'sortOptions',
            'selectedGenreNames', 'selectedTagNames', 'activeFilters',
        ));
    }

    public function updated(Request $request)
    {
        $period = $request->get('period', 'all');
        $periods = [
            'today' => ['label' => 'Hari Ini', 'days' => 1],
            'week' => ['label' => 'Minggu Ini', 'days' => 7],
            'month' => ['label' => 'Bulan Ini', 'days' => 30],
            'all' => ['label' => 'Semua', 'days' => null],
        ];
        if (!array_key_exists($period, $periods)) {
            $period = 'all';
        }

        $query = Novel::with(['author:id,name', 'genres:id,name', 'tags:id,name', 'chapters' => function ($q) {
                $q->published()->latest('published_at')->latest('id')->take(3);
            }])
            ->whereHas('chapters', function ($q) use ($periods, $period) {
                if ($periods[$period]['days']) {
                    $since = now()->subDays($periods[$period]['days']);
                    $q->published()
                        ->where(function ($sub) use ($since) {
                            $sub->where(function ($s) {
                                $s->whereNull('published_at')->orWhere('published_at', '<=', now());
                            })
                                ->where(function ($s) use ($since) {
                                    $s->where('published_at', '>=', $since)
                                        ->orWhere('created_at', '>=', $since);
                                });
                        });
                } else {
                    $q->published();
                }
            })
            ->withMax('chapters', 'published_at')
            ->withMax('chapters', 'created_at');

        if (Auth::check()) {
            $preferredIds = $this->recommendations->discoveryIds(Auth::user());

            if ($preferredIds !== []) {
                $bindings = [];
                $priority = collect($preferredIds)->values()->map(function ($novelId, $index) use (&$bindings) {
                    $bindings[] = $novelId;
                    $bindings[] = $index;

                    return 'WHEN novels.id = ? THEN ?';
                })->implode(' ');

                $bindings[] = count($preferredIds);
                $query->orderByRaw("CASE {$priority} ELSE ? END ASC", $bindings);
            }
        }

        $query->orderByRaw('COALESCE(chapters_max_published_at, chapters_max_created_at) DESC');

        $novels = $query->paginate(24)->withQueryString();

        return view('novels.updated', compact('novels', 'period', 'periods'));
    }

    public function genres()
    {
        $genres = Genre::withCount('novels')->orderBy('name')->get();

        return view('novels.genres', compact('genres'));
    }

    public function tags()
    {
        $tags = Tag::withCount('novels')->orderBy('name')->get();

        return view('novels.tags', compact('tags'));
    }

    public function history(Request $request)
    {
        $userId = Auth::id();

        $search = trim($request->query('q', ''));
        $dateRange = $request->query('date_range', 'all');
        $filterNovelId = $request->query('novel');

        $baseQuery = ReadingHistory::where('user_id', $userId)
            ->whereHas('novel')
            ->whereHas('chapter')
            ->with(['novel.author', 'chapter']);

        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->whereHas('novel', function ($qn) use ($search) {
                    $qn->where('title', 'like', "%{$search}%");
                })->orWhereHas('chapter', function ($qc) use ($search) {
                    $qc->where('title', 'like', "%{$search}%");
                });
            });
        }

        if ($filterNovelId && ctype_digit($filterNovelId)) {
            $baseQuery->where('novel_id', (int) $filterNovelId);
        }

        if (in_array($dateRange, ['7', '30', '90'], true)) {
            $baseQuery->where('created_at', '>=', now()->subDays((int) $dateRange));
        }

        $historiesRaw = $baseQuery->latest()->get();

        $historiesGrouped = [];
        $totalEntries = $historiesRaw->count();

        $novelsThisWeek = ReadingHistory::where('user_id', $userId)
            ->where('created_at', '>=', now()->startOfWeek())
            ->distinct('novel_id')
            ->count('novel_id');

        $bookmarkedNovelIds = Bookmark::where('user_id', $userId)->pluck('novel_id')->flip();

        foreach ($historiesRaw as $history) {
            $dateKey = $history->created_at->toDateString();

            $novel = $history->novel;
            $chapter = $history->chapter;

            $readChaptersCount = ReadingHistory::where('user_id', $userId)
                ->where('novel_id', $novel->id)
                ->distinct('chapter_id')
                ->count('chapter_id');
            $totalChapters = (int) ($novel->chapters_count ?? Chapter::where('novel_id', $novel->id)->count());
            $progress = $totalChapters > 0 ? min(($readChaptersCount / $totalChapters) * 100, 100) : 0;

            $nextChapter = Chapter::where('novel_id', $novel->id)
                ->where('order', '>', ($chapter->order ?? 0))
                ->orderBy('order')
                ->first();

            $isBookmarked = isset($bookmarkedNovelIds[$novel->id]);

            $history->read_chapters_count = $readChaptersCount;
            $history->total_chapters = $totalChapters;
            $history->progress_percentage = $progress;
            $history->next_chapter = $nextChapter;
            $history->is_bookmarked = $isBookmarked;
            $history->chapter_finished = $progress >= ($readChaptersCount / max($totalChapters, 1)) * 100 - 1;
            $history->time_spent_minutes = random_int(10, 25);

            if (! isset($historiesGrouped[$dateKey])) {
                $historiesGrouped[$dateKey] = [
                    'date' => $history->created_at->clone(),
                    'label' => $this->getHistoryDateLabel($history->created_at),
                    'items' => [],
                    'count' => 0,
                ];
            }
            $historiesGrouped[$dateKey]['items'][] = $history;
            $historiesGrouped[$dateKey]['count']++;
        }

        $perPage = 20;
        $page = (int) $request->query('page', 1);
        $flatItems = [];
        foreach ($historiesGrouped as $group) {
            foreach ($group['items'] as $h) $flatItems[] = $h;
        }

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($flatItems)->forPage($page, $perPage)->values(),
            $totalEntries,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $paginatedIds = $paginated->pluck('id')->flip();
        $paginatedGroups = [];
        foreach ($historiesGrouped as $dateKey => $group) {
            $filteredItems = [];
            foreach ($group['items'] as $h) {
                if (isset($paginatedIds[$h->id])) $filteredItems[] = $h;
            }
            if (count($filteredItems) > 0) {
                $paginatedGroups[$dateKey] = [
                    'date' => $group['date'],
                    'label' => $group['label'],
                    'items' => $filteredItems,
                    'count' => count($filteredItems),
                ];
            }
        }

        $userNovels = Novel::whereHas('readingHistories', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->orderBy('title')->get(['id', 'title']);

        $dateRangeOptions = [
            'all' => 'All time',
            '7' => 'Last 7 days',
            '30' => 'Last 30 days',
            '90' => 'Last 90 days',
        ];

        return view('user.history', compact(
            'historiesGrouped',
            'paginatedGroups',
            'paginated',
            'totalEntries',
            'novelsThisWeek',
            'userNovels',
            'dateRangeOptions',
            'dateRange',
            'search',
            'filterNovelId'
        ));
    }

    protected function getHistoryDateLabel(\Carbon\CarbonInterface $date): string
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $dateStr = $date->toDateString();

        if ($dateStr === $today) return 'TODAY';
        if ($dateStr === $yesterday) return 'YESTERDAY';

        return strtoupper($date->format('l'));
    }

    public function requests()
    {
        $requests = NovelRequest::with('user')->latest()->paginate(15)->withQueryString();

        return view('user.requests', compact('requests'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        NovelRequest::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Novel request submitted successfully!');
    }

    public function workspace(Novel $novel)
    {
        Gate::authorize('update', $novel);

        $chapters = Chapter::where('novel_id', $novel->id)->orderBy('order')->get();

        return view('writer.novels.workspace', compact('novel', 'chapters'));
    }

    public function create()
    {
        return redirect()->route('writer.novels.create.step-1');
    }

    public function createStep1()
    {
        return view('writer.novels.create-step-1');
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'alternative_title' => 'nullable|string|max:255',
            'status' => 'required|in:ongoing,hiatus,complete',
            'type' => 'required|in:web_novel,light_novel,original',
            'region' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'content_rating' => 'required|in:everyone,teen,mature',
        ]);

        $novel = new Novel;
        $novel->title = $validated['title'];
        $novel->alternative_title = $validated['alternative_title'] ?? null;
        $novel->slug = $this->generateUniqueSlug($validated['title']);
        $novel->status = $validated['status'];
        $novel->type = $validated['type'];
        $novel->region = $validated['region'] ?? null;
        $novel->language = $validated['language'] ?? null;
        $novel->content_rating = $validated['content_rating'];
        $novel->author_id = Auth::id();
        $novel->creation_step = 1;
        $novel->save();

        return redirect()
            ->route('writer.novels.create.step-2', $novel)
            ->with('success', 'Step 1 selesai. Lanjutkan isi sinopsis dan cover.');
    }

    public function createStep2(Novel $novel)
    {
        Gate::authorize('update', $novel);

        return view('writer.novels.create-step-2', compact('novel'));
    }

    public function updateStep2(Request $request, Novel $novel)
    {
        Gate::authorize('update', $novel);

        $validated = $request->validate([
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $novel->description = $validated['description'] ?? null;

        if ($request->hasFile('cover_image')) {
            if ($novel->cover_public_id) {
                $this->cloudinaryService->deleteImage($novel->cover_public_id);
            }

            $result = $this->cloudinaryService->uploadCover($request->file('cover_image'));
            $novel->cover_image_url = $result['url'];
            $novel->cover_public_id = $result['public_id'];
        }

        $novel->creation_step = max(2, (int) $novel->creation_step);
        $novel->save();

        return redirect()
            ->route('writer.novels.create.step-3', $novel)
            ->with('success', 'Step 2 selesai. Pilih genre dan tag novel.');
    }

    public function createStep3(Novel $novel)
    {
        Gate::authorize('update', $novel);

        $genres = Genre::all();
        $tags = Tag::all();
        $novel->load(['genres:id', 'tags:id']);

        return view('writer.novels.create-step-3', compact('novel', 'genres', 'tags'));
    }

    public function updateStep3(Request $request, Novel $novel)
    {
        Gate::authorize('update', $novel);

        $validated = $request->validate([
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $novel->genres()->sync($validated['genres']);
        $novel->tags()->sync($validated['tags'] ?? []);
        $novel->creation_step = 3;
        $novel->save();

        return redirect()
            ->route('dashboard', ['tab' => 'library'])
            ->with('success', 'Novel berhasil dibuat. Kamu bisa lanjut kelola karakter dari halaman novel.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'alternative_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:ongoing,hiatus,complete',
            'type' => 'required|in:web_novel,light_novel,original',
            'region' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'content_rating' => 'required|in:everyone,teen,mature',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'required|array',
            'tags' => 'nullable|array',
        ]);

        $slug = $this->generateUniqueSlug($request->title);

        $novel = new Novel;
        $novel->title = $request->title;
        $novel->alternative_title = $request->alternative_title;
        $novel->slug = $slug;
        $novel->description = $request->description;
        $novel->status = $request->status;
        $novel->type = $request->type;
        $novel->region = $request->region;
        $novel->language = $request->language;
        $novel->content_rating = $request->content_rating;
        $novel->author_id = Auth::id();

        if ($request->hasFile('cover_image')) {
            $result = $this->cloudinaryService->uploadCover($request->file('cover_image'));
            $novel->cover_image_url = $result['url'];
            $novel->cover_public_id = $result['public_id'];
        }

        $novel->save();

        $novel->genres()->sync($request->genres);
        if ($request->tags) {
            $novel->tags()->sync($request->tags);
        }

        return redirect()->route('dashboard', ['tab' => 'library'])->with('success', 'Novel created successfully!');
    }

    public function trending(Request $request)
    {
        $days = (int) $request->get('days', 7);
        if (! in_array($days, [7, 30], true)) {
            $days = 7;
        }

        $novels = $this->novelViews->trendingQuery(now()->subDays($days)->toDateString())
            ->paginate(24)
            ->withQueryString();

        $weeklyTop = $this->novelViews->trending(7, 5);

        return view('novels.trending', [
            'novels' => $novels,
            'days' => $days,
            'periodLabel' => $this->novelViews->periodLabel($days),
            'weeklyTop' => $weeklyTop,
        ]);
    }

    public function show(Novel $novel)
    {
        $this->novelViews->recordView($novel);

        $userLists = Auth::check()
            ? Auth::user()->userLists()->orderBy('title')->get(['id', 'slug', 'title'])
            : collect();

        $isAuthorOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || $novel->author_id === Auth::id());

        $novel->load(['author', 'genres', 'tags', 'characters', 'reviews.user', 'chapters' => function ($query) use ($isAuthorOrAdmin) {
            $query->select('id', 'novel_id', 'title', 'slug', 'status', 'published_at', 'created_at');
            if (! $isAuthorOrAdmin) {
                $query->published();
            }
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        }]);

        $lastReading = null;
        if (Auth::check()) {
            $lastReading = ReadingHistory::where('user_id', Auth::id())
                ->where('novel_id', $novel->id)
                ->with('chapter')
                ->latest()
                ->first();
        }

        // Personalized Recommendations: Novel Serupa berdasarkan Genre dan Tag
        $genreIds = $novel->genres->pluck('id');
        $tagIds = $novel->tags->pluck('id');

        $similarNovels = Novel::where('id', '!=', $novel->id)
            ->where(function ($query) use ($genreIds, $tagIds) {
                $query->whereHas('genres', function ($q) use ($genreIds) {
                    $q->whereIn('genres.id', $genreIds);
                })
                    ->orWhereHas('tags', function ($q) use ($tagIds) {
                        $q->whereIn('tags.id', $tagIds);
                    });
            })
            ->with(['author', 'genres'])
            ->withCount(['genres as matched_genres_count' => function ($query) use ($genreIds) {
                $query->whereIn('genres.id', $genreIds);
            }])
            ->withCount(['tags as matched_tags_count' => function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            }])
            ->orderByRaw('(matched_genres_count + matched_tags_count) DESC')
            ->take(6)
            ->get();

        return view('novels.show', compact('novel', 'similarNovels', 'lastReading', 'userLists'));
    }

    public function edit(Novel $novel)
    {
        Gate::authorize('update', $novel);
        $novel->load('characters');

        $genres = Genre::all();
        $tags = Tag::all();

        return view('writer.novels.edit', compact('novel', 'genres', 'tags'));
    }

    public function update(Request $request, Novel $novel)
    {
        Gate::authorize('update', $novel);

        $request->validate([
            'title' => 'required|string|max:255',
            'alternative_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:ongoing,hiatus,complete',
            'type' => 'required|in:web_novel,light_novel,original',
            'region' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'content_rating' => 'required|in:everyone,teen,mature',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'required|array',
            'tags' => 'nullable|array',
        ]);

        $novel->title = $request->title;
        $novel->alternative_title = $request->alternative_title;
        $novel->description = $request->description;
        $novel->status = $request->status;
        $novel->type = $request->type;
        $novel->region = $request->region;
        $novel->language = $request->language;
        $novel->content_rating = $request->content_rating;

        if ($request->hasFile('cover_image')) {
            if ($novel->cover_public_id) {
                $this->cloudinaryService->deleteImage($novel->cover_public_id);
            }
            $result = $this->cloudinaryService->uploadCover($request->file('cover_image'));
            $novel->cover_image_url = $result['url'];
            $novel->cover_public_id = $result['public_id'];
        }

        $novel->save();

        $novel->genres()->sync($request->genres);
        $novel->tags()->sync($request->tags ?? []);

        return redirect()->route('dashboard', ['tab' => 'library'])->with('success', 'Novel updated successfully!');
    }

    public function destroy(Novel $novel)
    {
        Gate::authorize('delete', $novel);

        if ($novel->cover_public_id) {
            $this->cloudinaryService->deleteImage($novel->cover_public_id);
        }

        foreach ($novel->characters as $character) {
            if ($character->image_public_id) {
                $this->cloudinaryService->deleteImage($character->image_public_id);
            }
        }

        $novel->delete();

        return redirect()->route('dashboard', ['tab' => 'library'])->with('success', 'Novel deleted successfully!');
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (Novel::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
