<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Novel;
use App\Models\Tag;
use App\Models\NovelRequest;
use App\Models\ReadingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NovelController extends Controller
{
    public function index(Request $request)
    {
        $query = Novel::with(['author', 'genres']);

        if ($request->genre) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('slug', $request->genre);
            });
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $novels = $query->latest()->paginate(12)->withQueryString();
        
        // Recently Updated: Novels with the most recent chapters
        $recentlyUpdated = Novel::with(['author', 'genres'])
            ->whereHas('chapters')
            ->withMax('chapters', 'created_at')
            ->orderByDesc('chapters_max_created_at')
            ->take(6)
            ->get();

        $genres = Genre::all();

        return view('novels.index', compact('novels', 'genres', 'recentlyUpdated'));
    }

    public function updated()
    {
        $novels = Novel::with(['author', 'genres'])
            ->whereHas('chapters')
            ->withMax('chapters', 'created_at')
            ->orderByDesc('chapters_max_created_at')
            ->paginate(18);

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
            ->paginate(15);

        return view('user.history', compact('histories'));
    }

    public function requests()
    {
        $requests = NovelRequest::with('user')->latest()->paginate(15);
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
        $novels = Novel::where('author_id', Auth::id())->latest()->get();
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
            'content_rating' => 'required|in:everyone,teen,mature',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'required|array',
            'tags' => 'nullable|array',
        ]);

        $slug = Str::slug($request->title);
        $count = Novel::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $novel = new Novel();
        $novel->title = $request->title;
        $novel->alternative_title = $request->alternative_title;
        $novel->slug = $slug;
        $novel->description = $request->description;
        $novel->status = $request->status;
        $novel->type = $request->type;
        $novel->content_rating = $request->content_rating;
        $novel->author_id = Auth::id();

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $novel->cover_image = $path;
        }

        $novel->save();

        $novel->genres()->sync($request->genres);
        if ($request->tags) {
            $novel->tags()->sync($request->tags);
        }

        return redirect()->route('writer.novels.index')->with('success', 'Novel created successfully!');
    }

    public function show(Novel $novel)
    {
        $novel->increment('view_count');
        $novel->load(['author', 'chapters', 'genres', 'tags', 'reviews.user']);
        return view('novels.show', compact('novel'));
    }

    public function edit(Novel $novel)
    {
        if (Auth::user()->role !== 'admin' && $novel->author_id !== Auth::id()) {
            abort(403);
        }

        $genres = Genre::all();
        $tags = Tag::all();
        return view('writer.novels.edit', compact('novel', 'genres', 'tags'));
    }

    public function update(Request $request, Novel $novel)
    {
        if (Auth::user()->role !== 'admin' && $novel->author_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'alternative_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:ongoing,hiatus,complete',
            'type' => 'required|in:web_novel,light_novel,original',
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
        $novel->content_rating = $request->content_rating;

        if ($request->hasFile('cover_image')) {
            if ($novel->cover_image) {
                Storage::disk('public')->delete($novel->cover_image);
            }
            $path = $request->file('cover_image')->store('covers', 'public');
            $novel->cover_image = $path;
        }

        $novel->save();

        $novel->genres()->sync($request->genres);
        $novel->tags()->sync($request->tags ?? []);

        return redirect()->route('writer.novels.index')->with('success', 'Novel updated successfully!');
    }

    public function destroy(Novel $novel)
    {
        if (Auth::user()->role !== 'admin' && $novel->author_id !== Auth::id()) {
            abort(403);
        }

        if ($novel->cover_image) {
            Storage::disk('public')->delete($novel->cover_image);
        }

        $novel->delete();

        return redirect()->route('writer.novels.index')->with('success', 'Novel deleted successfully!');
    }
}
