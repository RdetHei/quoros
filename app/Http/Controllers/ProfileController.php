<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $readingList = [];
        if ($user->is_public_reading_list) {
            $readingList = $user->bookmarks()->with('novel')->latest()->get();
        }

        return view('profile.show', compact('user', 'readingList'));
    }
}
