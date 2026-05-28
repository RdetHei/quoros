<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Services\CloudinaryService;
use App\Models\Genre;
use App\Models\Novel;
use App\Models\NovelRequest;
use App\Models\ReadingHistory;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NovelController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function landing()
    {
        // Recently Updated: Novels with the most recent chapters
        $recentlyUpdated = Novel::with(['author', 'genres', 'chapters' => function($q) {
                $q->published()->latest()->take(1);
            }])
            ->whereHas('chapters')
            ->withMax('chapters', 'created_at')
            ->orderByDesc('chapters_max_created_at')
            ->take(8)
            ->get();

        return view('welcome', compact('recentlyUpdated'));
    }

    public function index(Request $request)
    {
        $query = Novel::with(['author', 'genres']);

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
            ->whereHas('chapters')
            ->withMax('chapters', 'created_at')
            ->orderByDesc('chapters_max_created_at')
            ->take(6)
            ->get();

        $genres = Genre::all();

        // Leaderboard: Top Novels Weekly & Monthly
        $weeklyTop = Novel::with(['author', 'genres'])
            ->withCount(['chapters', 'bookmarks'])
            ->where('created_at', '>=', now()->subDays(7))
            ->orderByDesc('view_count')
            ->take(5)
            ->get();

        $monthlyTop = Novel::with(['author', 'genres'])
            ->withCount(['chapters', 'bookmarks'])
            ->where('created_at', '>=', now()->subMonth())
            ->orderByDesc('view_count')
            ->take(5)
            ->get();

        return view('novels.index', compact('novels', 'genres', 'recentlyUpdated', 'weeklyTop', 'monthlyTop', 'featuredNovels'));
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $genre = $request->get('genre');
        $status = $request->get('status');
        $type = $request->get('type');
        $tag = $request->get('tag');
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

        // Sorting
        switch ($sort) {
            case 'rating':
                $query->orderByDesc('rating_avg');
                break;
            case 'views':
                $query->orderByDesc('view_count');
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

        return view('novels.search', compact('novels', 'search', 'genres', 'tags'));
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

        return back()->with('success', 'Permintaan novel berhasil dikirim!');
    }

    public function writerIndex()
    {
        $novels = Novel::where('author_id', Auth::id())
            ->withCount(['chapters', 'bookmarks'])
            ->latest()
            ->get();

        return view('writer.novels.index', compact('novels'));
    }

    public function create()
    {
        $genres = Genre::all();
        $tags = Tag::all();

        return view('writer.novels.create', compact('genres', 'tags'));
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
            'character_name' => 'nullable|array',
            'character_name.*' => 'nullable|string|max:255',
            'character_role' => 'nullable|array',
            'character_role.*' => 'nullable|string|max:255',
            'character_description' => 'nullable|array',
            'character_description.*' => 'nullable|string|max:1000',
            'character_image' => 'nullable|array',
            'character_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'existing_character_image' => 'nullable|array',
            'existing_character_image.*' => 'nullable|string',
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
        $this->syncCharacters($novel, $request);

        return redirect()->route('writer.novels.index')->with('success', 'Novel created successfully!');
    }

    public function show(Novel $novel)
    {
        $novel->increment('view_count');

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

        return view('novels.show', compact('novel', 'similarNovels', 'lastReading'));
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
            'character_name' => 'nullable|array',
            'character_name.*' => 'nullable|string|max:255',
            'character_role' => 'nullable|array',
            'character_role.*' => 'nullable|string|max:255',
            'character_description' => 'nullable|array',
            'character_description.*' => 'nullable|string|max:1000',
            'character_image' => 'nullable|array',
            'character_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'existing_character_image' => 'nullable|array',
            'existing_character_image.*' => 'nullable|string',
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
        $this->syncCharacters($novel, $request);

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

    private function syncCharacters(Novel $novel, Request $request): void
    {
        $oldPublicIds = $novel->characters()->pluck('image_public_id')->filter()->values();
        $novel->characters()->delete();

        $names = collect($request->input('character_name', []));
        $roles = collect($request->input('character_role', []));
        $descriptions = collect($request->input('character_description', []));
        /** @var Collection<int, UploadedFile|null> $images */
        $images = collect($request->file('character_image', []));
        $existingImages = collect($request->input('existing_character_image', []));
        $existingPublicIds = collect($request->input('existing_character_public_id', []));
        $newPublicIds = collect();

        $names->each(function ($name, $index) use ($novel, $roles, $descriptions, $images, $existingImages, $existingPublicIds, $newPublicIds): void {
            $cleanName = trim((string) $name);
            if ($cleanName === '') {
                return;
            }

            $imagePath = null;
            $localImage = null;
            $publicId = null;
            $uploadedImage = $images->get($index);

            if ($uploadedImage) {
                $result = $this->cloudinaryService->uploadCharacter($uploadedImage);
                $imagePath = $result['url'];
                $publicId = $result['public_id'];
                $newPublicIds->push($publicId);
            } else {
                $existingImage = $existingImages->get($index);
                $existingPublicId = $existingPublicIds->get($index);
                
                if (is_string($existingImage) && $existingImage !== '') {
                    if (str_starts_with($existingImage, 'http')) {
                        $imagePath = $existingImage;
                        $publicId = $existingPublicId;
                        $newPublicIds->push($publicId);
                    } else {
                        $localImage = $existingImage;
                    }
                }
            }

            $novel->characters()->create([
                'name' => $cleanName,
                'role' => trim((string) $roles->get($index, '')) ?: null,
                'description' => trim((string) $descriptions->get($index, '')) ?: null,
                'image_url' => $imagePath,
                'image' => $localImage,
                'image_public_id' => $publicId,
                'sort_order' => $index,
            ]);
        });

        $oldPublicIds->diff($newPublicIds)->each(function ($publicId): void {
            $this->cloudinaryService->deleteImage($publicId);
        });
    }
}
