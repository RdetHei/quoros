@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <!-- Header with Background Decoration -->
    <div class="relative mb-10 p-6 md:p-10 rounded-[2.5rem] bg-slate-900 overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-emerald-500/10 rounded-full -ml-24 -mb-24 blur-3xl"></div>
        
        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 rounded-full text-emerald-400 text-[10px] font-bold uppercase tracking-widest mb-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Writer Analytics
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-2">Statistik Penulis</h1>
                <p class="text-slate-400 font-medium max-w-lg">Pantau performa setiap kata yang kamu tulis dan bangun komunitas pembacamu.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <!-- Novel Selector -->
                <form action="{{ route('writer.stats') }}" method="GET" id="novelFilterForm" class="w-full sm:w-72">
                    <div class="relative group">
                        <select name="novel_id" onchange="document.getElementById('novelFilterForm').submit()" 
                            class="w-full bg-slate-800/50 backdrop-blur-md border border-slate-700 rounded-2xl px-5 py-3.5 text-sm font-bold text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Semua Novel</option>
                            @foreach($allNovels as $novel)
                                <option value="{{ $novel->id }}" {{ $selectedNovelId == $novel->id ? 'selected' : '' }}>
                                    {{ $novel->title }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                </form>

                <a href="{{ route('writer.novels.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white text-slate-900 font-black rounded-2xl hover:bg-emerald-50 transition-all text-sm shadow-xl shadow-white/5 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    Kelola Novel
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <!-- Views -->
        <div class="group bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:rotate-12 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Pembaca</p>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalViews) }}</h3>
                </div>
            </div>
        </div>

        <!-- Bookmarks -->
        <div class="group bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-rose-500/5 transition-all duration-300 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-500/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-400 group-hover:rotate-12 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Bookmark</p>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalBookmarks) }}</h3>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        <div class="group bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300 relative overflow-hidden sm:col-span-2 lg:col-span-1">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:rotate-12 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Ulasan</p>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalReviews) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Main Growth Chart -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white">Pertumbuhan Aktivitas</h3>
                    <p class="text-sm text-slate-400 font-medium">Data 30 hari terakhir</p>
                </div>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                        <span class="text-xs font-bold text-slate-400">Bookmark</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="text-xs font-bold text-slate-400">Ulasan</span>
                    </div>
                </div>
            </div>
            <div class="h-[400px]">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Sidebar Charts Column -->
        <div class="space-y-8">
            <!-- Bookmarks vs Reviews Doughnut -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
                <h3 class="text-xl font-black text-slate-800 dark:text-white mb-8 text-center">Interaksi Rasio</h3>
                <div class="h-[240px] relative">
                    <canvas id="interactionChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</p>
                        <p class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($totalBookmarks + $totalReviews) }}</p>
                    </div>
                </div>
                <div class="mt-8 space-y-3">
                    <div class="flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Bookmark</span>
                        </div>
                        <span class="text-xs font-black text-slate-900 dark:text-white">{{ $totalBookmarks }}</span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Ulasan</span>
                        </div>
                        <span class="text-xs font-black text-slate-900 dark:text-white">{{ $totalReviews }}</span>
                    </div>
                </div>
            </div>

            <!-- Readers per Day Bar -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white">Pembaca Harian</h3>
                    <div class="p-2 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                </div>
                <div class="h-[200px]">
                    <canvas id="readersChart"></canvas>
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

    // Custom Plugin for Tooltips
    const tooltipStyles = {
        backgroundColor: 'rgba(15, 23, 42, 0.9)',
        titleFont: { size: 13, weight: 'bold' },
        bodyFont: { size: 12 },
        padding: 12,
        cornerRadius: 12,
        displayColors: true,
        usePointStyle: true,
    };

    // 1. Growth Chart
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Bookmark',
                    data: bookmarkData,
                    borderColor: '#f43f5e',
                    borderWidth: 4,
                    pointBackgroundColor: '#f43f5e',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    fill: true,
                    backgroundColor: (context) => {
                        const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(244, 63, 94, 0.15)');
                        gradient.addColorStop(1, 'rgba(244, 63, 94, 0)');
                        return gradient;
                    },
                    tension: 0.4
                },
                {
                    label: 'Ulasan',
                    data: reviewData,
                    borderColor: '#f59e0b',
                    borderWidth: 4,
                    pointBackgroundColor: '#f59e0b',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    fill: true,
                    backgroundColor: (context) => {
                        const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(245, 158, 11, 0.15)');
                        gradient.addColorStop(1, 'rgba(245, 158, 11, 0)');
                        return gradient;
                    },
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: tooltipStyles
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                    ticks: { font: { size: 11, weight: '600' }, color: '#94a3b8' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, weight: '600' }, color: '#94a3b8', maxRotation: 0 }
                }
            }
        }
    });

    // 2. Readers Chart
    new Chart(document.getElementById('readersChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pembaca',
                data: readerData,
                backgroundColor: '#4f46e5',
                borderRadius: 6,
                hoverBackgroundColor: '#6366f1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: tooltipStyles
            },
            scales: {
                y: { display: false },
                x: { display: false }
            }
        }
    });

    // 3. Interaction Chart
    new Chart(document.getElementById('interactionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Bookmark', 'Ulasan'],
            datasets: [{
                data: [{{ $totalBookmarks }}, {{ $totalReviews }}],
                backgroundColor: ['#f43f5e', '#f59e0b'],
                borderWidth: 8,
                borderColor: document.documentElement.classList.contains('dark') ? '#0f172a' : '#fff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: {
                legend: { display: false },
                tooltip: tooltipStyles
            }
        }
    });
</script>
@endpush
@endsection
