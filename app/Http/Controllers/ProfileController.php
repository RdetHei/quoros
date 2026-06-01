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
        $isFollowing = $user->isFollowedBy($viewer?->id);
        $canFollow = $user->canBeFollowed() && ! $isOwner;
        $canViewReadingList = $user->is_public_reading_list || $isOwner || ($viewer && $viewer->role === 'admin');

        $readingList = collect();
        if ($canViewReadingList) {
            $readingList = $user->bookmarks()->with(['novel.author'])->latest()->get();
        }

        $reviews = $user->reviews()->with('novel')->latest()->get();

        $writerStats = null;
        if (in_array($user->role, ['writer', 'admin'], true)) {
            $myNovels = $user->novels()->with(['chapters.comments'])->get();
            $writerStats = [
                'total_views' => $myNovels->sum('view_count'),
                'total_comments' => $myNovels->sum(fn ($n) => $n->chapters->sum(fn ($c) => $c->comments->count())),
                'avg_rating' => $myNovels->avg('rating_avg') ?? 0,
                'novel_count' => $myNovels->count(),
            ];
        }

        $publicLists = $user->userLists()
            ->where('is_public', true)
            ->withCount('items')
            ->latest()
            ->get();

        return view('profile.show', compact(
            'user',
            'readingList',
            'reviews',
            'canViewReadingList',
            'writerStats',
            'isOwner',
            'isFollowing',
            'canFollow',
            'publicLists',
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

        return back()->with('success', 'Profile photo updated successfully!');
    }
}
