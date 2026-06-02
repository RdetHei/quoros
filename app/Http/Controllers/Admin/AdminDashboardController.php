<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Novel;
use App\Models\Report;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalActiveUsers = User::where('is_banned', false)->count();
        $totalNovels = Novel::count();

        // Points/Revenue isn't modeled explicitly in current DB schema.
        // We'll keep it as a placeholder metric for the UI.
        $totalRevenuePoints = 0;

        $pendingReports = Report::where('status', ReportStatus::Pending->value)->count();

        $latestUsers = User::latest()->take(6)->get(['id', 'name', 'role', 'created_at']);
        $latestNovelUpdates = Chapter::query()
            ->with(['novel:id,title,author_id', 'novel.author:id,name'])
            ->latest('created_at')
            ->take(6)
            ->get(['id', 'novel_id', 'title', 'created_at']);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalActiveUsers',
            'totalNovels',
            'totalRevenuePoints',
            'pendingReports',
            'latestUsers',
            'latestNovelUpdates'
        ));
    }
}

