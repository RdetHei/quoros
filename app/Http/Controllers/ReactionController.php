<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function toggle(Request $request, $type, $id)
    {
        $request->validate([
            'reaction_type' => 'required|in:like,dislike',
        ]);

        $modelMap = [
            'comment' => Comment::class,
        ];

        if (!isset($modelMap[$type])) {
            return back()->with('error', 'Invalid reaction type.');
        }

        $modelClass = $modelMap[$type];
        $model = $modelClass::findOrFail($id);

        $existingReaction = Reaction::where('user_id', Auth::id())
            ->where('reactable_id', $id)
            ->where('reactable_type', $modelClass)
            ->first();

        if ($existingReaction) {
            if ($existingReaction->type === $request->reaction_type) {
                $existingReaction->delete();
                return back()->with('success', 'Reaction removed.');
            } else {
                $existingReaction->update(['type' => $request->reaction_type]);
                return back()->with('success', 'Reaction updated.');
            }
        }

        Reaction::create([
            'user_id' => Auth::id(),
            'reactable_id' => $id,
            'reactable_type' => $modelClass,
            'type' => $request->reaction_type,
        ]);

        return back()->with('success', 'Reaction added.');
    }
}
