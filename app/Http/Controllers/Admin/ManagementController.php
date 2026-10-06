<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Novel;
use App\Models\ReadingSession;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    public function users(Request $request)
    {
        $role = $request->string('role')->value() ?: 'all';
        $status = $request->string('status')->value() ?: $request->string('ban')->value() ?: 'all';
        $search = trim($request->string('q')->value());
        $sort = $request->string('sort')->value() ?: 'recent';

        $users = User::query()
            ->withCount(['novels', 'followers', 'readingHistories'])
            ->withMax('readingSessions', 'last_active_at')
            ->when($role !== 'all', fn ($query) => $query->where('role', $role))
            ->when($status === 'restricted', fn ($query) => $query->where(fn ($query) => $query->where('is_banned', true)->orWhere('banned_until', '>', now())))
            ->when($status === 'active', fn ($query) => $query->where('is_banned', false)->where(fn ($query) => $query->whereNull('banned_until')->orWhere('banned_until', '<=', now()))->whereNotNull('email_verified_at'))
            ->when($status === 'review', fn ($query) => $query->whereNull('email_verified_at'))
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->when($sort === 'activity', fn ($query) => $query->orderByDesc('reading_sessions_max_last_active_at')->orderByDesc('created_at'))
            ->when($sort === 'oldest', fn ($query) => $query->oldest())
            ->when(! in_array($sort, ['activity', 'oldest'], true), fn ($query) => $query->latest())
            ->paginate(7)
            ->withQueryString();

        $selectedUser = User::query()
            ->withCount(['novels', 'followers', 'readingHistories'])
            ->withMax('readingSessions', 'last_active_at')
            ->find($request->integer('selected'));
        $selectedUser ??= $users->first();

        $userStats = [
            'total' => User::count(),
            'activeToday' => ReadingSession::query()->whereDate('last_active_at', today())->distinct('user_id')->count('user_id'),
            'authors' => User::where('role', 'writer')->count(),
            'restricted' => User::where(fn ($query) => $query->where('is_banned', true)->orWhere('banned_until', '>', now()))->count(),
        ];

        $activityStart = today()->subDays(6);
        $activityEnd = today()->endOfDay();
        $membersByDay = User::query()->selectRaw('DATE(created_at) as activity_date, COUNT(*) as total')
            ->whereBetween('created_at', [$activityStart, $activityEnd])->groupByRaw('DATE(created_at)')->pluck('total', 'activity_date');
        $readersByDay = ReadingSession::query()->selectRaw('DATE(last_active_at) as activity_date, COUNT(DISTINCT user_id) as total')
            ->whereBetween('last_active_at', [$activityStart, $activityEnd])->groupByRaw('DATE(last_active_at)')->pluck('total', 'activity_date');
        $reviewsByDay = Review::query()->selectRaw('DATE(created_at) as activity_date, COUNT(*) as total')
            ->whereBetween('created_at', [$activityStart, $activityEnd])->groupByRaw('DATE(created_at)')->pluck('total', 'activity_date');
        $commentsByDay = Comment::query()->selectRaw('DATE(created_at) as activity_date, COUNT(*) as total')
            ->whereBetween('created_at', [$activityStart, $activityEnd])->groupByRaw('DATE(created_at)')->pluck('total', 'activity_date');

        $memberActivity = collect(range(6, 0))->map(function (int $daysAgo) use ($membersByDay, $readersByDay, $reviewsByDay, $commentsByDay) {
            $date = today()->subDays($daysAgo);
            $key = $date->toDateString();

            return [
                'label' => $date->format('D'),
                'members' => (int) ($membersByDay[$key] ?? 0),
                'readers' => (int) ($readersByDay[$key] ?? 0),
                'reviews' => (int) ($reviewsByDay[$key] ?? 0),
                'comments' => (int) ($commentsByDay[$key] ?? 0),
            ];
        });

        return view('admin.users.index', compact('users', 'role', 'status', 'search', 'sort', 'selectedUser', 'userStats', 'memberActivity'));
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
