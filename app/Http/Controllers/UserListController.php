<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use App\Models\User;
use App\Models\UserList;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserListController extends Controller
{
    public function index()
    {
        $lists = Auth::user()->userLists()->withCount('items')->latest()->get();

        return view('user.lists.index', compact('lists'));
    }

    public function create()
    {
        return view('user.lists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['sometimes', 'boolean'],
        ]);

        $slug = $this->uniqueSlug(Auth::id(), Str::slug($validated['title']));

        $list = Auth::user()->userLists()->create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'is_public' => $request->boolean('is_public'),
        ]);
        app(RecommendationService::class)->forgetForUser(Auth::id());

        return redirect()->route('lists.show', $list)->with('success', 'List berhasil dibuat.');
    }

    public function show(UserList $list)
    {
        $this->authorizeList($list);

        $list->load(['novels.author', 'novels.genres']);

        $isOwner = Auth::check() && (int) Auth::id() === (int) $list->user_id;

        $pickableNovels = collect();
        if ($isOwner) {
            $excludedIds = $list->novels->pluck('id')->all();
            $bookmarkQ = Auth::user()->bookmarks()->with('novel:id,title,slug,cover_image,cover_image_url,author_id')->latest();
            if (count($excludedIds) > 0) {
                $bookmarkQ->whereNotIn('novel_id', $excludedIds);
            }
            $bookmarked = $bookmarkQ->limit(30)->get()->pluck('novel')->filter();

            $recentsQ = Novel::with(['author:id,name'])->select(['id', 'title', 'slug', 'cover_image', 'cover_image_url', 'author_id'])->latest();
            if (count($excludedIds) > 0) {
                $recentsQ->whereNotIn('id', $excludedIds);
            }
            $recents = $recentsQ->limit(20)->get();

            $pickableNovels = $bookmarked->merge($recents)->unique('id')->take(50)->values();
        }

        return view('user.lists.show', compact('list', 'isOwner', 'pickableNovels'));
    }

    public function showPublic(string $username, UserList $list)
    {
        $owner = User::where('username', $username)
            ->when(ctype_digit($username), fn ($q) => $q->orWhere('id', (int) $username))
            ->firstOrFail();

        abort_unless($list->user_id === $owner->id, 404);
        abort_unless($list->is_public || (Auth::check() && Auth::id() === $owner->id), 403);

        $list->load(['user', 'novels.author', 'novels.genres']);

        $isOwner = Auth::check() && (int) Auth::id() === (int) $owner->id;

        $pickableNovels = collect();
        if ($isOwner) {
            $excludedIds = $list->novels->pluck('id')->all();
            $bookmarkQ = Auth::user()->bookmarks()->with('novel:id,title,slug,cover_image,cover_image_url,author_id')->latest();
            if (count($excludedIds) > 0) {
                $bookmarkQ->whereNotIn('novel_id', $excludedIds);
            }
            $bookmarked = $bookmarkQ->limit(30)->get()->pluck('novel')->filter();

            $recentsQ = Novel::with(['author:id,name'])->select(['id','title','slug','cover_image','cover_image_url','author_id'])->latest();
            if (count($excludedIds) > 0) {
                $recentsQ->whereNotIn('id', $excludedIds);
            }
            $recents = $recentsQ->limit(20)->get();

            $pickableNovels = $bookmarked->merge($recents)->unique('id')->take(50)->values();
        }

        return view('user.lists.show', [
            'list' => $list,
            'isOwner' => $isOwner,
            'pickableNovels' => $pickableNovels,
        ]);
    }

    public function edit(UserList $list)
    {
        $this->authorizeOwner($list);

        return view('user.lists.edit', compact('list'));
    }

    public function update(Request $request, UserList $list)
    {
        $this->authorizeOwner($list);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['sometimes', 'boolean'],
        ]);

        $slug = $list->slug;
        if ($list->title !== $validated['title']) {
            $slug = $this->uniqueSlug($list->user_id, Str::slug($validated['title']), $list->id);
        }

        $list->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'is_public' => $request->boolean('is_public'),
        ]);
        app(RecommendationService::class)->forgetForUser(Auth::id());

        return redirect()->route('lists.show', $list)->with('success', 'List updated.');
    }

    public function destroy(UserList $list)
    {
        $this->authorizeOwner($list);
        $list->delete();
        app(RecommendationService::class)->forgetForUser(Auth::id());

        return redirect()->route('lists.index')->with('success', 'List deleted successfully.');
    }

    public function addNovel(Request $request, UserList $list, ?Novel $novel = null)
    {
        $this->authorizeOwner($list);

        $provided = $request->input('novel_id')
            ?? $request->input('novel')
            ?? (isset($novel->id) ? $novel->id : null);

        if ($provided === null) {
            return back()->with('error', 'Please choose a novel to add.');
        }

        $resolved = null;
        if (is_numeric($provided)) {
            $resolved = Novel::find((int) $provided);
        } elseif (is_string($provided) && trim($provided) !== '') {
            $resolved = Novel::where('slug', trim($provided))->first()
                ?? Novel::find((int) $provided);
        }

        if (! $resolved) {
            return back()->with('error', 'Novel not found.');
        }

        if ($list->novels()->where('novel_id', $resolved->id)->exists()) {
            return back()->with('error', $resolved->title . ' is already in this list.');
        }

        $list->novels()->attach($resolved->id);
        app(RecommendationService::class)->forgetForUser(Auth::id());

        return back()->with('success', '"' . $resolved->title . '" added to your list.');
    }

    public function removeNovel(UserList $list, $novel = null)
    {
        $this->authorizeOwner($list);

        $resolved = null;
        if ($novel instanceof Novel) {
            $resolved = $novel;
        } elseif (is_numeric($novel)) {
            $resolved = Novel::find((int) $novel);
        } elseif (is_string($novel) && trim($novel) !== '') {
            $resolved = Novel::where('slug', trim($novel))->first();
        }

        if (! $resolved) {
            return back()->with('error', 'Novel not found.');
        }

        $existed = $list->novels()->where('novel_id', $resolved->id)->exists();
        $list->novels()->detach($resolved->id);
        app(RecommendationService::class)->forgetForUser(Auth::id());

        if (! $existed) {
            return back()->with('info', 'Novel was not in this list.');
        }

        return back()->with('success', 'Novel removed from list.');
    }

    private function authorizeOwner(UserList $list): void
    {
        abort_unless(Auth::check() && Auth::id() === $list->user_id, 403);
    }

    private function authorizeList(UserList $list): void
    {
        if (Auth::check() && Auth::id() === $list->user_id) {
            return;
        }

        abort_unless($list->is_public, 403);
    }

    private function uniqueSlug(int $userId, string $base, ?int $exceptId = null): string
    {
        $slug = $base ?: 'list';
        $original = $slug;
        $count = 1;

        while (
            UserList::query()
                ->where('user_id', $userId)
                ->where('slug', $slug)
                ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
                ->exists()
        ) {
            $slug = $original.'-'.$count++;
        }

        return $slug;
    }
}
