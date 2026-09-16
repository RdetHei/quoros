<?php

namespace App\Http\Controllers;

use App\Models\InAppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = InAppNotification::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        $hasUnread = InAppNotification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->exists();

        return view('notifications.index', compact('notifications', 'hasUnread'));
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
