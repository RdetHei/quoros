<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(string $username)
    {
        $user = User::query()
            ->where('username', $username)
            ->when(ctype_digit($username), fn ($q) => $q->orWhere('id', (int) $username))
            ->withCount(['reviews', 'bookmarks'])
            ->firstOrFail();

        $viewer = Auth::user();
        $isOwner = $viewer && (int) $viewer->id === (int) $user->id;
        $canViewReadingList = $user->is_public_reading_list || $isOwner || ($viewer && $viewer->role === 'admin');

        $readingList = collect();
        if ($canViewReadingList) {
            $readingList = $user->bookmarks()->with(['novel.author'])->latest()->get();
        }

        $works = collect();
        if (in_array($user->role, ['writer', 'admin'], true)) {
            $works = $user->novels()->latest('updated_at')->get();
        }

        $reviews = $user->reviews()->with('novel')->latest()->get();

        return view('profile.show', compact(
            'user',
            'readingList',
            'works',
            'reviews',
            'canViewReadingList'
        ));
    }
}
