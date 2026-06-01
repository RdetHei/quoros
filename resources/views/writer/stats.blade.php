@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        @include('partials.writer-nav', ['active' => 'stats'])

        <div class="lg:col-span-9 space-y-6">
            {{-- Page header --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-10 bg-indigo-600 rounded-full shrink-0"></div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Writer Dashboard</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Your work performance statistics in the last 30 days.</p>
                    </div>
                </div>

                <form action="{{ route('writer.stats') }}" method="GET" id="novelFilterForm" class="w-full sm:w-64">
                    <label for="novel_id" class="sr-only">Filter novel</label>
                    <select name="novel_id" id="novel_id" onchange="document.getElementById('novelFilterForm').submit()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">All Novels</option>
                        @foreach($allNovels as $novel)
                            <option value="{{ $novel->id }}" {{ $selectedNovelId == $novel->id ? 'selected' : '' }}>{{ $novel->title }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- KPI cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Readers</p>
                        <div class="p-1.5 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalViews) }}</p>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Bookmarks</p>
                        <div class="p-1.5 bg-rose-50 dark:bg-rose-900/30 rounded-lg text-rose-600 dark:text-rose-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalBookmarks) }}</p>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Reviews</p>
                        <div class="p-1.5 bg-amber-50 dark:bg-amber-900/30 rounded-lg text-amber-600 dark:text-amber-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalReviews) }}</p>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Interaction Growth</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daily bookmarks & reviews</p>
                        </div>
                        <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Bookmarks</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Reviews</span>
                        </div>
                    </div>
                    <div class="h-[320px]">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-1">Interaction Ratio</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-5">Bookmarks vs reviews</p>
                        <div class="h-[180px] relative">
                            <canvas id="interactionChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wide">Total</p>
                                <p class="text-xl font-bold text-slate-900 dark:text-white">{{ number_format($totalBookmarks + $totalReviews) }}</p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2 text-slate-600 dark:text-slate-400"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Bookmarks</span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($totalBookmarks) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2 text-slate-600 dark:text-slate-400"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Reviews</span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($totalReviews) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-1">Aktivitas Membaca</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-5">Riwayat baca harian</p>
                        <div class="h-[140px]">
                            <canvas id="readersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($labels);
    const bookmarkData = @json($bookmarkData);
    const reviewData = @json($reviewData);
    const readerData = @json($readerData);
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(148, 163, 184, 0.1)' : 'rgba(0, 0, 0, 0.04)';
    const tickColor = '#94a3b8';

    const tooltipStyles = {
        backgroundColor: isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(15, 23, 42, 0.9)',
        titleFont: { size: 12, weight: '600' },
        bodyFont: { size: 11 },
        padding: 10,
        cornerRadius: 8,
        displayColors: true,
        usePointStyle: true,
    };

    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Bookmark',
                    data: bookmarkData,
                    borderColor: '#f43f5e',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: true,
                    backgroundColor: 'rgba(244, 63, 94, 0.08)',
                    tension: 0.35,
                },
                {
                    label: 'Ulasan',
                    data: reviewData,
                    borderColor: '#f59e0b',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: true,
                    backgroundColor: 'rgba(245, 158, 11, 0.08)',
                    tension: 0.35,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: { legend: { display: false }, tooltip: tooltipStyles },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor, drawBorder: false }, ticks: { font: { size: 11 }, color: tickColor } },
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: tickColor, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
            },
        },
    });

    new Chart(document.getElementById('readersChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Pembaca',
                data: readerData,
                backgroundColor: '#6366f1',
                borderRadius: 4,
                hoverBackgroundColor: '#818cf8',
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: tooltipStyles },
            scales: { y: { display: false }, x: { display: false } },
        },
    });

    new Chart(document.getElementById('interactionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Bookmark', 'Ulasan'],
            datasets: [{
                data: [{{ $totalBookmarks }}, {{ $totalReviews }}],
                backgroundColor: ['#f43f5e', '#f59e0b'],
                borderWidth: 4,
                borderColor: isDark ? '#0f172a' : '#ffffff',
                hoverOffset: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: { legend: { display: false }, tooltip: tooltipStyles },
        },
    });
</script>
@endpush
@endsection
