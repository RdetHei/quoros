<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Services\CloudinaryService;
use App\Services\NovelViewService;
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
        private NovelViewService $novelViews,
    ) {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function landing()
    {
        // Featured Novels for Carousel
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
                $q->published()->latest()->take(1);
            }])
            ->withCount('chapters')
            ->whereHas('chapters')
            ->withMax('chapters', 'created_at')
            ->orderByDesc('chapters_max_created_at')
            ->take(8)
            ->get();

        $stats = [
            'novels' => Novel::count(),
            'chapters' => Chapter::published()->count(),
            'genres' => Genre::count(),
            'updates_week' => Chapter::published()
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return view('welcome', compact('recentlyUpdated', 'featuredNovels', 'stats'));
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

        $weeklyTop = $this->novelViews->trending(7, 5);
        $monthlyTop = $this->novelViews->trending(30, 5);

        return view('novels.index', compact(
            'novels',
            'genres',
            'popularTags',
            'recentlyUpdated',
            'weeklyTop',
            'monthlyTop',
            'featuredNovels',
        ));
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $genre = $request->get('genre');
        $status = $request->get('status');
        $type = $request->get('type');
        $tag = $request->get('tag');
        $minRating = $request->get('min_rating');
        $sort = $request->get('sort', 'latest');

        $query = Novel::with(['author', 'genres']);

        // Keyword search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('alternative_title', 'like', "%{$search}%")
                    ->orWhereHas('author', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Genre filter
        if ($genre) {
            $query->whereHas('genres', function ($q) use ($genre) {
                $q->where('slug', $genre);
            });
        }

        // Status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Type filter
        if ($type) {
            $query->where('type', $type);
        }

        // Tag filter
        if ($tag) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('slug', $tag);
            });
        }

        if ($minRating !== null && $minRating !== '') {
            $query->where('rating_avg', '>=', (float) $minRating);
        }

        // Sorting
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
            default: // 'latest'
                $query->latest();
                break;
        }

        $novels = $query->paginate(24)->withQueryString();
        $genres = Genre::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('novels.search', compact('novels', 'search', 'genres', 'tags', 'minRating'));
    }

    public function updated()
    {
        $novels = Novel::with(['author', 'genres'])
            ->whereHas('chapters')
            ->withMax('chapters', 'created_at')
            ->withCount('chapters')
            ->orderByDesc('chapters_max_created_at')
            ->paginate(18)
            ->withQueryString();

        return view('novels.updated', compact('novels'));
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

    public function history()
    {
        $histories = ReadingHistory::where('user_id', Auth::id())
            ->whereHas('novel')
            ->whereHas('chapter')
            ->with(['novel.author', 'chapter'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('user.history', compact('histories'));
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

    public function writerIndex()
    {
        $userId = Auth::id();

        $novels = Novel::where('author_id', $userId)
            ->with('genres')
            ->withCount(['chapters', 'bookmarks'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'novel_count' => Novel::where('author_id', $userId)->count(),
            'chapter_count' => Novel::where('author_id', $userId)->withCount('chapters')->get()->sum('chapters_count'),
            'total_views' => Novel::where('author_id', $userId)->sum('view_count'),
            'total_bookmarks' => Novel::where('author_id', $userId)->withCount('bookmarks')->get()->sum('bookmarks_count'),
        ];

        return view('writer.novels.index', compact('novels', 'summary'));
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
            ->route('writer.novels.index')
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

        $slug = Str::slug($request->title);
        $count = Novel::where('slug', 'like', $slug.'%')->count();
        if ($count > 0) {
            $slug = $slug.'-'.($count + 1);
        }

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

        return redirect()->route('writer.novels.index')->with('success', 'Novel created successfully!');
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
            ? Auth::user()->userLists()->orderBy('title')->get(['id', 'title'])
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

        return redirect()->route('writer.novels.index')->with('success', 'Novel updated successfully!');
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

        return redirect()->route('writer.novels.index')->with('success', 'Novel deleted successfully!');
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = Novel::where('slug', 'like', $slug.'%')->count();

        if ($count > 0) {
            $slug .= '-'.($count + 1);
        }

        return $slug;
    }
}
