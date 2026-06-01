<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use App\Models\ReadingHistory;
use App\Models\Announcement;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function index(Request $request)
    {
        if ($request->get('tab') === 'settings') {
            return redirect()->route('settings');
        }

        $user = Auth::user();

        // 1. Stats Ringkas
        // Simulasi jam baca (karena belum ada tracking di DB)
        $totalReadingHours = $user->readingHistories()->count() * 0.5; // Anggap 1 chapter = 30 menit
        $favoriteNovel = $user->bookmarks()->first()?->novel;
        $userPoints = 1250; // Simulasi poin/koin

        // 2. Lanjutkan Membaca (Hero Section)
        $lastRead = $user->readingHistories()
            ->whereHas('novel')
            ->whereHas('chapter')
            ->with(['novel', 'chapter'])
            ->latest()
            ->first();

        if ($lastRead && $lastRead->novel) {
            $totalChapters = $lastRead->novel->chapters()->count();
            // Hitung posisi chapter saat ini (berdasarkan ID)
            $currentChapterPos = $lastRead->novel->chapters()
                ->where('id', '<=', $lastRead->chapter_id)
                ->count();
            
            $lastRead->progress = $totalChapters > 0 ? round(($currentChapterPos / $totalChapters) * 100) : 0;
            $lastRead->current_pos = $currentChapterPos;
            $lastRead->total_chapters = $totalChapters;
        }

        // 3. Statistik Penulis dipindah ke halaman profil

        // 4. Tabbed Content
        $bookmarks = $user->bookmarks()
            ->whereHas('novel')
            ->with('novel.author')
            ->latest()
            ->get();
            
        $histories = $user->readingHistories()
            ->whereHas('novel')
            ->whereHas('chapter')
            ->with(['novel', 'chapter'])
            ->latest()
            ->take(10)
            ->get();
        $recommendations = Novel::whereNotIn('id', $bookmarks->pluck('novel_id'))
            ->orderBy('rating_avg', 'desc')
            ->take(6)
            ->get();

        $announcements = Announcement::where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'user', 
            'totalReadingHours', 
            'favoriteNovel', 
            'userPoints',
            'lastRead',
            'bookmarks',
            'histories',
            'recommendations',
            'announcements'
        ));
    }

    public function settings()
    {
        return view('settings.index', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['nullable', 'string', 'alpha_dash', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'bio' => 'nullable|string|max:500',
            'is_public_reading_list' => 'nullable|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'bio']);
        $data['username'] = $request->filled('username') ? $request->username : null;
        $data['is_public_reading_list'] = $request->boolean('is_public_reading_list');

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_public_id) {
                $this->cloudinaryService->deleteImage($user->profile_photo_public_id);
            }
            $result = $this->cloudinaryService->uploadProfile($request->file('profile_photo'));
            $data['profile_photo_url'] = $result['url'];
            $data['profile_photo_public_id'] = $result['public_id'];
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function becomeWriter(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'user') {
            return back()->with('error', 'You already have contributor access.');
        }

        // Update role to writer
        $user->role = 'writer';
        $user->save();

        return back()->with('success', 'Congratulations! Your account has been successfully changed to Writer. You can now start creating your own novels.');
    }
}
