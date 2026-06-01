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

    public function markRead(InAppNotification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        $notification->markAsRead();

        if ($url = $notification->url()) {
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
