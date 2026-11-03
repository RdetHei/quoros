<?php

namespace App\Http\Controllers;

use App\Models\PlotNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlotNoteController extends Controller
{
    private const CATEGORIES = ['scene_outline', 'character_arc', 'world', 'continuity', 'general'];

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|in:' . implode(',', self::CATEGORIES),
            'content' => 'nullable|string',
            'tags' => 'nullable|string',
            'novel_id' => 'nullable|integer|exists:novels,id',
            'is_open_question' => 'nullable|boolean',
            'is_pinned' => 'nullable|boolean',
        ]);

        $note = PlotNote::create([
            'user_id' => Auth::id(),
            'novel_id' => $this->ownedNovelId($validated['novel_id'] ?? null),
            'title' => $validated['title'],
            'category' => $validated['category'] ?? 'general',
            'content' => $validated['content'] ?? null,
            'tags' => $this->parseTags($validated['tags'] ?? null),
            'is_open_question' => $request->boolean('is_open_question'),
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        return back()->with('success', 'Catatan plot berhasil dibuat.');
    }

    public function update(Request $request, PlotNote $plotNote)
    {
        abort_unless($plotNote->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|in:' . implode(',', self::CATEGORIES),
            'content' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_open_question' => 'nullable|boolean',
        ]);

        $plotNote->update([
            'title' => $validated['title'],
            'category' => $validated['category'] ?? $plotNote->category,
            'content' => $validated['content'] ?? null,
            'tags' => $this->parseTags($validated['tags'] ?? null),
            'is_open_question' => $request->boolean('is_open_question'),
        ]);

        return back()->with('success', 'Catatan plot berhasil diperbarui.');
    }

    public function destroy(PlotNote $plotNote)
    {
        abort_unless($plotNote->user_id === Auth::id(), 403);

        $plotNote->delete();

        return back()->with('success', 'Catatan plot dihapus.');
    }

    public function togglePin(PlotNote $plotNote)
    {
        abort_unless($plotNote->user_id === Auth::id(), 403);

        $plotNote->update(['is_pinned' => ! $plotNote->is_pinned]);

        return back();
    }

    private function ownedNovelId(?int $novelId): ?int
    {
        if (! $novelId) {
            return null;
        }

        return Auth::user()->novels()->whereKey($novelId)->exists() ? $novelId : null;
    }

    private function parseTags(?string $raw): ?array
    {
        if (! $raw) {
            return null;
        }

        $tags = array_values(array_filter(array_map('trim', explode(',', $raw))));

        return $tags ?: null;
    }
}
