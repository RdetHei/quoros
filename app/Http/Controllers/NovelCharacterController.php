<?php

namespace App\Http\Controllers;

use App\Models\NovelCharacter;
use App\Models\Novel;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NovelCharacterController extends Controller
{
    public function __construct(
        private CloudinaryService $cloudinaryService,
    ) {}

    public function index(Novel $novel): View
    {
        Gate::authorize('update', $novel);
        $novel->load('characters');

        return view('writer.novels.characters.index', compact('novel'));
    }

    public function create(Novel $novel): View
    {
        Gate::authorize('update', $novel);

        return view('writer.novels.characters.create', compact('novel'));
    }

    public function store(Request $request, Novel $novel): RedirectResponse
    {
        Gate::authorize('update', $novel);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $payload = [
            'name' => $validated['name'],
            'role' => $validated['role'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => ((int) $novel->characters()->max('sort_order')) + 1,
        ];

        if ($request->hasFile('photo')) {
            $result = $this->cloudinaryService->uploadCharacter($request->file('photo'));
            $payload['image_url'] = $result['url'];
            $payload['image_public_id'] = $result['public_id'];
            $payload['image'] = null;
        }

        $novel->characters()->create($payload);

        return redirect()
            ->route('writer.novels.characters.index', $novel)
            ->with('success', 'Karakter berhasil ditambahkan.');
    }

    public function edit(Novel $novel, NovelCharacter $character): View
    {
        Gate::authorize('update', $novel);
        $this->ensureCharacterBelongsToNovel($novel, $character);

        return view('writer.novels.characters.edit', compact('novel', 'character'));
    }

    public function update(Request $request, Novel $novel, NovelCharacter $character): RedirectResponse
    {
        Gate::authorize('update', $novel);
        $this->ensureCharacterBelongsToNovel($novel, $character);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $payload = [
            'name' => $validated['name'],
            'role' => $validated['role'] ?? null,
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($character->image_public_id) {
                $this->cloudinaryService->deleteImage($character->image_public_id);
            }

            $result = $this->cloudinaryService->uploadCharacter($request->file('photo'));
            $payload['image_url'] = $result['url'];
            $payload['image_public_id'] = $result['public_id'];
            $payload['image'] = null;
        }

        $character->update($payload);

        return redirect()
            ->route('writer.novels.characters.index', $novel)
            ->with('success', 'Data karakter berhasil diperbarui.');
    }

    public function destroy(Novel $novel, NovelCharacter $character): RedirectResponse
    {
        Gate::authorize('update', $novel);
        $this->ensureCharacterBelongsToNovel($novel, $character);

        if ($character->image_public_id) {
            $this->cloudinaryService->deleteImage($character->image_public_id);
        }

        $character->delete();

        return redirect()
            ->route('writer.novels.characters.index', $novel)
            ->with('success', 'Karakter berhasil dihapus.');
    }

    private function ensureCharacterBelongsToNovel(Novel $novel, NovelCharacter $character): void
    {
        abort_unless($character->novel_id === $novel->id, 404);
    }
}
