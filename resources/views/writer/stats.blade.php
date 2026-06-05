@extends('layouts.dashboard', [
    'title' => 'Analytics Engine',
    'subtitle' => 'Deep dive into your story performance and reader behavior.'
])

@section('dashboard-content')
<div class="space-y-10">
    {{-- Filter Header --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white">Performance Overview</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Showing data for the last 30 days.</p>
        </div>

        <form action="{{ route('writer.stats') }}" method="GET" id="novelFilterForm" class="w-full md:w-80">
            <select name="novel_id" id="novel_id" onchange="document.getElementById('novelFilterForm').submit()"
                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                <option value="">All Managed Novels</option>
                @foreach($allNovels as $novel)
                    <option value="{{ $novel->id }}" {{ $selectedNovelId == $novel->id ? 'selected' : '' }}>{{ $novel->title }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Readers</p>
                <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalViews) }}</p>
        </div>
        
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Bookmarks</p>
                <div class="w-10 h-10 bg-rose-50 dark:bg-rose-900/30 rounded-xl flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalBookmarks) }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Reviews</p>
                <div class="w-10 h-10 bg-amber-50 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalReviews) }}</p>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-10">
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white">Engagement Trend</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Daily interactions across works</p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500 shadow-lg shadow-rose-500/20"></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bookmarks</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500 shadow-lg shadow-amber-500/20"></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Reviews</span>
                    </div>
                </div>
            </div>
            <div class="h-[400px]">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-4 flex flex-col gap-8">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-sm flex-grow">
                <h2 class="text-xl font-black text-slate-900 dark:text-white mb-2">Interaction Ratio</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-10">Bookmarks vs Reviews share</p>
                
                <div class="h-[220px] relative mb-10">
                    <canvas id="interactionChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Grand Total</p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($totalBookmarks + $totalReviews) }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-rose-500 shadow-lg shadow-rose-500/20"></div>
                            <span class="text-xs font-bold text-slate-500">Bookmarks</span>
                        </div>
                        <span class="text-sm font-black text-slate-900 dark:text-white">{{ number_format($totalBookmarks) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-amber-500 shadow-lg shadow-amber-500/20"></div>
                            <span class="text-xs font-bold text-slate-500">Reviews</span>
                        </div>
                        <span class="text-sm font-black text-slate-900 dark:text-white">{{ number_format($totalReviews) }}</span>
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
