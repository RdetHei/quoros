<?php

namespace App\Http\Controllers;

use App\Models\CharacterRelationship;
use App\Models\Novel;
use App\Models\NovelCharacter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WriterRelationshipController extends Controller
{
    public function store(Request $request, Novel $novel)
    {
        Gate::authorize('update', $novel);

        $validated = $request->validate([
            'character_id' => 'required|integer|exists:novel_characters,id',
            'related_character_id' => 'nullable|integer|exists:novel_characters,id',
            'related_name' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'status' => 'nullable|in:established,unresolved',
        ]);

        abort_unless(
            $novel->characters()->whereKey($validated['character_id'])->exists(),
            404
        );

        if (! empty($validated['related_character_id'])) {
            abort_unless(
                $novel->characters()->whereKey($validated['related_character_id'])->exists(),
                404
            );
        }

        $novel->characterRelationships()->create([
            'character_id' => $validated['character_id'],
            'related_character_id' => $validated['related_character_id'] ?? null,
            'related_name' => $validated['related_name'] ?? null,
            'note' => $validated['note'] ?? null,
            'status' => $validated['status'] ?? 'unresolved',
        ]);

        return back()->with('success', 'Relasi karakter berhasil ditambahkan.');
    }

    public function destroy(Novel $novel, CharacterRelationship $relationship)
    {
        Gate::authorize('update', $novel);
        abort_unless($relationship->novel_id === $novel->id, 404);

        $relationship->delete();

        return back()->with('success', 'Relasi karakter dihapus.');
    }
}
