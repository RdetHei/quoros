<?php

namespace App\Http\Controllers;

use App\Enums\NotificationType;
use App\Models\InAppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filter = (string) $request->query('filter', 'all');
        if (! in_array($filter, ['all', 'unread', 'announcements'], true)) {
            $filter = 'all';
        }

        $base = InAppNotification::query()->where('user_id', Auth::id());

        $notifications = (clone $base)
            ->when($filter === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->when($filter === 'announcements', fn ($query) => $query->where('type', NotificationType::Announcement->value))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $totalCount = (clone $base)->count();
        $unreadCount = (clone $base)->whereNull('read_at')->count();
        $announcementCount = (clone $base)->where('type', NotificationType::Announcement->value)->count();

        $hasUnread = $unreadCount > 0;

        return view('notifications.index', compact(
            'notifications',
            'hasUnread',
            'filter',
            'totalCount',
            'unreadCount',
            'announcementCount',
        ));
    }

    public function show(InAppNotification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        $notification->markAsRead();

        return view('notifications.show', compact('notification'));
    }

    public function markRead(InAppNotification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        $notification->markAsRead();

        $url = $notification->url();

        if ($notification->type === \App\Enums\NotificationType::Announcement && $notification->url()) {
            return redirect()->route('notifications.show', $notification);
        }

        if ($url) {
            return redirect($url);
        }

        return back();
    }

    public function readAll(Request $request)
    {
        InAppNotification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
