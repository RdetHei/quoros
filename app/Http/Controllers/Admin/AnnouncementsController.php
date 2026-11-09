<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\InAppNotificationService;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    public function __construct(private readonly InAppNotificationService $notifications)
    {
    }

    public function index()
    {
        $announcements = Announcement::latest()->paginate(20);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'type' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
            'link' => ['nullable', 'string', 'max:1000'],
        ]);

        $announcement = Announcement::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => $data['type'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'link' => $data['link'] ?? null,
        ]);

        if ($announcement->is_active) {
            $this->notifications->notifyAnnouncementToAllUsers(
                $announcement->title,
                $announcement->content,
                $announcement->link,
                $announcement->type,
            );
        }

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'type' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
            'link' => ['nullable', 'string', 'max:1000'],
        ]);

        $wasActive = $announcement->is_active;

        $announcement->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => $data['type'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'link' => $data['link'] ?? null,
        ]);

        // Kirim notifikasi hanya saat pengumuman baru saja diaktifkan.
        if (! $wasActive && $announcement->is_active) {
            $this->notifications->notifyAnnouncementToAllUsers(
                $announcement->title,
                $announcement->content,
                $announcement->link,
                $announcement->type,
            );
        }

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function toggle(Announcement $announcement)
    {
        $announcement->update(['is_active' => ! $announcement->is_active]);

        if ($announcement->is_active) {
            $this->notifications->notifyAnnouncementToAllUsers(
                $announcement->title,
                $announcement->content,
                $announcement->link,
                $announcement->type,
            );
        }

        return back()->with('success', $announcement->is_active
            ? 'Announcement diaktifkan.'
            : 'Announcement dinonaktifkan.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }
}

