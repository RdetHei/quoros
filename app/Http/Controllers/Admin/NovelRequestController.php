<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NovelRequest;
use Illuminate\Http\Request;

class NovelRequestController extends Controller
{
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

        $novelRequest->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status permintaan berhasil diperbarui!');
    }

    public function destroy(NovelRequest $novelRequest)
    {
        $novelRequest->delete();
        return back()->with('success', 'Permintaan berhasil dihapus!');
    }
}
