<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use App\Models\NovelRequest;
use App\Services\InAppNotificationService;
use Illuminate\Http\Request;

class NovelRequestController extends Controller
{
    public function __construct(
        private InAppNotificationService $notifications,
    ) {}

    public function index()
    {
        $requests = NovelRequest::with('user')->latest()->paginate(20);
        return view('admin.requests.index', compact('requests'));
    }

    public function updateStatus(Request $request, NovelRequest $novelRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,fulfilled,rejected',
        ]);

        $previousStatus = $novelRequest->status;

        $novelRequest->update([
            'status' => $request->status,
        ]);

        if ($previousStatus !== $request->status) {
            if ($request->status === 'fulfilled') {
                $this->notifications->notifyRequestStatus($novelRequest, NotificationType::RequestFulfilled);
            } elseif ($request->status === 'rejected') {
                $this->notifications->notifyRequestStatus($novelRequest, NotificationType::RequestRejected);
            }
        }

        return back()->with('success', 'Status permintaan berhasil diperbarui!');
    }

    public function destroy(NovelRequest $novelRequest)
    {
        $novelRequest->delete();
        return back()->with('success', 'Permintaan berhasil dihapus!');
    }
}
