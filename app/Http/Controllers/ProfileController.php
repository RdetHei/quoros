<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Models\User;
use App\Services\CloudinaryService;
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

        $reviews = $user->reviews()->with('novel')->latest()->get();

        return view('profile.show', compact(
            'user',
            'readingList',
            'reviews',
            'canViewReadingList'
        ));
    }

    public function updateProfilePhoto(ImageUploadRequest $request, CloudinaryService $cloudinaryService)
    {
        $user = Auth::user();
        
        $file = $request->file('image') ?: $request->file('profile_photo');
        
        if (!$file) {
            return back()->with('error', 'Tidak ada foto yang diunggah.');
        }

        if ($user->profile_photo_public_id) {
            $cloudinaryService->deleteImage($user->profile_photo_public_id);
        }

        $result = $cloudinaryService->uploadProfile($file);

        $user->update([
            'profile_photo_url' => $result['url'],
            'profile_photo_public_id' => $result['public_id'],
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
