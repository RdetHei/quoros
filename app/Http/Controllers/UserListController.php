<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use App\Models\User;
use App\Models\UserList;
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

        return redirect()->route('lists.show', $list)->with('success', 'List berhasil dibuat.');
    }

    public function show(UserList $list)
    {
        $this->authorizeList($list);

        $list->load(['novels.author', 'novels.genres']);

        return view('user.lists.show', compact('list'));
    }

    public function showPublic(string $username, UserList $list)
    {
        $owner = User::where('username', $username)
            ->when(ctype_digit($username), fn ($q) => $q->orWhere('id', (int) $username))
            ->firstOrFail();

        abort_unless($list->user_id === $owner->id, 404);
        abort_unless($list->is_public || (Auth::check() && Auth::id() === $owner->id), 403);

        $list->load(['user', 'novels.author', 'novels.genres']);

        $isOwner = Auth::check() && Auth::id() === $owner->id;

        return view('user.lists.show', [
            'list' => $list,
            'isOwner' => $isOwner,
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

        return redirect()->route('lists.show', $list)->with('success', 'List diperbarui.');
    }

    public function destroy(UserList $list)
    {
        $this->authorizeOwner($list);
        $list->delete();

        return redirect()->route('lists.index')->with('success', 'List dihapus.');
    }

    public function addNovel(Request $request, UserList $list, Novel $novel)
    {
        $this->authorizeOwner($list);

        $list->novels()->syncWithoutDetaching([$novel->id]);

        return back()->with('success', 'Novel ditambahkan ke list.');
    }

    public function removeNovel(UserList $list, Novel $novel)
    {
        $this->authorizeOwner($list);
        $list->novels()->detach($novel->id);

        return back()->with('success', 'Novel dihapus dari list.');
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
