<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Novel;
use App\Models\Tag;
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
        $genres = Genre::all();

        return view('novels.index', compact('novels', 'genres'));
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
            'description' => 'nullable|string',
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
        $novel->slug = $slug;
        $novel->description = $request->description;
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
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'required|array',
            'tags' => 'nullable|array',
        ]);

        $novel->title = $request->title;
        $novel->description = $request->description;

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
