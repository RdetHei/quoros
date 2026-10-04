<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\ReadingHistory;
use App\Models\User;
use App\Models\UserList;
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

        $readingList = collect();
        $enrichedBookmarks = collect();
        $statsCounts = [
            'saved' => 0,
            'reading' => 0,
            'completed' => 0,
        ];
        $continueJourney = collect();
        $onShelf = collect();

        if ($canViewReadingList) {
            $rawBookmarks = $user->bookmarks()
                ->whereHas('novel')
                ->with([
                    'novel.author',
                    'novel.genres:id,name,slug',
                ])
                ->withCount('novel as total_chapters')
                ->latest('bookmarks.updated_at')
                ->get();

            $statsCounts['saved'] = $rawBookmarks->count();

            foreach ($rawBookmarks as $bm) {
                $lastRead = ReadingHistory::where('user_id', $user->id)
                    ->where('novel_id', $bm->novel_id)
                    ->latest()
                    ->first();

                $readChaptersCount = ReadingHistory::where('user_id', $user->id)
                    ->where('novel_id', $bm->novel_id)
                    ->distinct('chapter_id')
                    ->count('chapter_id');

                $totalChapters = (int) ($bm->total_chapters ?? Chapter::where('novel_id', $bm->novel_id)->count());
                $progress = $totalChapters > 0 ? min(($readChaptersCount / $totalChapters) * 100, 100) : 0;
                $novelStatus = (string) ($bm->novel->status ?? '');

                if ($progress >= 100 || ($novelStatus === 'completed' && $progress >= 95)) {
                    $readingStatus = 'completed';
                    $statsCounts['completed']++;
                } elseif ($readChaptersCount > 0) {
                    $readingStatus = 'reading';
                    $statsCounts['reading']++;
                } else {
                    $readingStatus = 'plan';
                }

                $bm->reading_status = $readingStatus;
                $bm->read_chapters_count = $readChaptersCount;
                $bm->total_chapters = $totalChapters;
                $bm->progress_percentage = $progress;
                $bm->last_read_chapter = $lastRead ? $lastRead->chapter : null;
                $bm->last_read_at = $lastRead ? $lastRead->created_at : null;

                $enrichedBookmarks->push($bm);
            }

            $continueJourney = $enrichedBookmarks
                ->where('reading_status', 'reading')
                ->sortByDesc(fn ($b) => $b->last_read_at?->timestamp ?? 0)
                ->take(4)
                ->values();

            $onShelf = $enrichedBookmarks->take(8)->values();
            $readingList = $enrichedBookmarks;
        }

        $userListsQuery = $user->userLists()
            ->withCount('novels')
            ->with(['novels' => function ($query) {
                $query->select(['novels.id', 'novels.title', 'novels.cover_image', 'novels.cover_image_url'])
                    ->limit(3);
            }])
            ->latest();

        if (! $isOwner) {
            $userListsQuery->where('is_public', true);
        }
        $userLists = $userListsQuery->get();

        $publicListsCount = $user->userLists()->where('is_public', true)->count();
        $privateListsCount = $userLists->where('is_public', false)->count();
        $totalListsCount = $userLists->count();

        $historyCount = ReadingHistory::where('user_id', $user->id)->count();
        $totalHistoryMinutes = $historyCount * 12;
        $readingHours = (int) floor($totalHistoryMinutes / 60);

        return view('profile.show', compact(
            'user',
            'readingList',
            'enrichedBookmarks',
            'reviews',
            'canViewReadingList',
            'writerStats',
            'isOwner',
            'isFollowing',
            'canFollow',
            'statsCounts',
            'continueJourney',
            'onShelf',
            'userLists',
            'publicListsCount',
            'privateListsCount',
            'totalListsCount',
            'readingHours',
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
