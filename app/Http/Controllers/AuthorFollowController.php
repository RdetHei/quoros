<?php

namespace App\Http\Controllers;

use App\Models\AuthorFollow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthorFollowController extends Controller
{
    public function toggle(User $user)
    {
        if ((int) $user->id === (int) Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengikuti diri sendiri.');
        }

        if (! $user->canBeFollowed()) {
            return back()->with('error', 'Pengguna ini belum dapat diikuti.');
        }

        $existing = AuthorFollow::query()
            ->where('follower_id', Auth::id())
            ->where('author_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with('success', 'Stopped following '.$user->name.'.');
        }

        AuthorFollow::create([
            'follower_id' => Auth::id(),
            'author_id' => $user->id,
        ]);

        return back()->with('success', 'You are now following '.$user->name.'.');
    }
}
