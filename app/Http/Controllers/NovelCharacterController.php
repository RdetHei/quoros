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

        $validated = $this->validateCharacter($request);

        $payload = [
            'name' => $validated['name'],
            'tagline' => $validated['tagline'] ?? null,
            'role' => $validated['role'] ?? null,
            'cast_type' => $validated['cast_type'] ?? 'supporting',
            'description' => $validated['description'] ?? null,
            'tags' => $this->parseTags($validated['tags'] ?? null),
            'anchor' => $validated['anchor'] ?? null,
            'current_chapter' => $validated['current_chapter'] ?? null,
            'wants' => $validated['wants'] ?? null,
            'fears' => $validated['fears'] ?? null,
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

        $validated = $this->validateCharacter($request);

        $payload = [
            'name' => $validated['name'],
            'tagline' => $validated['tagline'] ?? null,
            'role' => $validated['role'] ?? null,
            'cast_type' => $validated['cast_type'] ?? 'supporting',
            'description' => $validated['description'] ?? null,
            'tags' => $this->parseTags($validated['tags'] ?? null),
            'anchor' => $validated['anchor'] ?? null,
            'current_chapter' => $validated['current_chapter'] ?? null,
            'wants' => $validated['wants'] ?? null,
            'fears' => $validated['fears'] ?? null,
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

    private function validateCharacter(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'cast_type' => 'nullable|in:main,supporting',
            'description' => 'nullable|string|max:2000',
            'tags' => 'nullable|string|max:500',
            'anchor' => 'nullable|string|max:255',
            'current_chapter' => 'nullable|string|max:255',
            'wants' => 'nullable|string|max:2000',
            'fears' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    private function parseTags(?string $raw): ?array
    {
        if (! $raw) {
            return null;
        }

        $tags = array_values(array_filter(array_map('trim', explode(',', $raw))));

        return $tags ?: null;
    }

    private function ensureCharacterBelongsToNovel(Novel $novel, NovelCharacter $character): void
    {
        abort_unless($character->novel_id === $novel->id, 404);
    }
}
