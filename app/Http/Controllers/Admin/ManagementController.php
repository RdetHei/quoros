<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Novel;
use App\Models\User;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    public function users(Request $request)
    {
        $role = $request->get('role', 'all');
        $banStatus = $request->get('ban', 'all');

        $users = User::query()
            ->when($role !== 'all', fn ($query) => $query->where('role', $role))
            ->when($banStatus === 'banned', fn ($query) => $query->where('is_banned', true))
            ->when($banStatus === 'active', fn ($query) => $query->where('is_banned', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'role', 'banStatus'));
    }

    public function moderation()
    {
        $novels = Novel::with('author:id,name')
            ->latest()
            ->paginate(20);

        $pendingReports = \App\Models\Report::where('status', ReportStatus::Pending->value)->count();

        return view('admin.moderation.index', compact('novels', 'pendingReports'));
    }

    public function contentLogs()
    {
        $recentChapters = Chapter::with('novel:id,title,author_id', 'novel.author:id,name')
            ->latest()
            ->paginate(25);

        return view('admin.content-logs.index', compact('recentChapters'));
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:user,writer,admin'],
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'User role updated successfully!');
    }
}
