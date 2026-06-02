@extends('layouts.dashboard')

@php
    $dashboardTitle = 'The Platform Pulse';
    $dashboardSubtitle = 'Control center for platform performance and moderation health.';
    $dashboardBreadcrumbs = ['Dashboard', 'Admin'];
@endphp

@section('dashboard-content')
<section class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">Active Users</p>
        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($activeUsersCount) }}</p>
    </article>
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">Total Novels</p>
        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalNovelsCount) }}</p>
    </article>
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">Active Writers/Admins</p>
        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($writerCount) }}</p>
    </article>
</section>

<section class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <h2 class="font-semibold text-slate-900 dark:text-white">Moderation Alerts</h2>
        <div class="mt-4 space-y-2 text-sm">
            <p class="text-slate-600 dark:text-slate-300">Pending reports: <span class="font-semibold">{{ number_format($pendingReports) }}</span></p>
            <p class="text-slate-600 dark:text-slate-300">New novel requests: <span class="font-semibold">{{ number_format($newNovelRequests) }}</span></p>
        </div>
    </article>
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <h2 class="font-semibold text-slate-900 dark:text-white">Growth Snapshot (30 days)</h2>
        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Chart-ready data is prepared for frontend chart integration.</p>
        <dl class="mt-4 text-sm text-slate-600 dark:text-slate-300 space-y-1">
            <div class="flex justify-between"><dt>Latest user growth/day</dt><dd>{{ (int) collect($userGrowth)->last() }}</dd></div>
            <div class="flex justify-between"><dt>Latest novel growth/day</dt><dd>{{ (int) collect($novelGrowth)->last() }}</dd></div>
            <div class="flex justify-between"><dt>Total labels</dt><dd>{{ count($trendLabels) }} days</dd></div>
        </dl>
    </article>
</section>

<section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
    <h2 class="font-semibold text-slate-900 dark:text-white">Recent Activity</h2>
    <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4 text-sm">
        <div>
            <h3 class="font-medium text-slate-700 dark:text-slate-200">New Users</h3>
            <ul class="mt-2 space-y-1 text-slate-600 dark:text-slate-300">
                @forelse($recentUsers as $recentUser)
                    <li>{{ $recentUser->name }} ({{ $recentUser->role }})</li>
                @empty
                    <li>No user activity yet.</li>
                @endforelse
            </ul>
        </div>
        <div>
            <h3 class="font-medium text-slate-700 dark:text-slate-200">New Novels</h3>
            <ul class="mt-2 space-y-1 text-slate-600 dark:text-slate-300">
                @forelse($recentNovels as $recentNovel)
                    <li>{{ $recentNovel->title }} - {{ $recentNovel->author?->name ?? 'Unknown' }}</li>
                @empty
                    <li>No novel activity yet.</li>
                @endforelse
            </ul>
        </div>
        <div>
            <h3 class="font-medium text-slate-700 dark:text-slate-200">New Chapters</h3>
            <ul class="mt-2 space-y-1 text-slate-600 dark:text-slate-300">
                @forelse($recentChapters as $recentChapter)
                    <li>{{ $recentChapter->title }} - {{ $recentChapter->novel?->title ?? 'Unknown Novel' }}</li>
                @empty
                    <li>No chapter activity yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</section>
@endsection
