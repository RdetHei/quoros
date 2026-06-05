@extends('layouts.dashboard', [
    'title' => 'Reading Hub',
    'subtitle' => 'Continue reading, manage bookmarks, and stay on your daily goal.'
])

@section('dashboard-content')
<section class="bg-slate-900 dark:bg-indigo-950 rounded-2xl p-6 text-white">
    @if($lastRead)
        <p class="text-xs uppercase tracking-wider text-indigo-300">Continue Reading</p>
        <h2 class="mt-2 text-2xl font-bold">{{ $lastRead->novel->title }}</h2>
        <p class="mt-1 text-sm text-slate-300">Last chapter: {{ $lastRead->chapter->title }}</p>
        <div class="mt-4">
            <div class="flex justify-between text-xs text-slate-300 mb-1">
                <span>Progress</span>
                <span>{{ $lastRead->progress ?? 0 }}%</span>
            </div>
            <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                <div class="h-2 bg-indigo-400 rounded-full" style="width: {{ $lastRead->progress ?? 0 }}%"></div>
            </div>
        </div>
        <a href="{{ route('chapters.show', [$lastRead->novel->slug, $lastRead->chapter->slug]) }}" class="inline-flex mt-5 px-4 py-2 rounded-xl bg-white text-slate-900 text-sm font-semibold">
            Continue
        </a>
    @else
        <h2 class="text-xl font-bold">No active reading session yet</h2>
        <p class="mt-1 text-sm text-slate-300">Start a new story from homepage recommendations.</p>
        <a href="{{ route('home') }}" class="inline-flex mt-5 px-4 py-2 rounded-xl bg-white text-slate-900 text-sm font-semibold">
            Browse Novels
        </a>
    @endif
</section>

<section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <article class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <h2 class="font-semibold text-slate-900 dark:text-white">Bookmarks</h2>
        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-3">
            @forelse($bookmarks as $bookmark)
                <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="rounded-xl border border-slate-100 dark:border-slate-800 p-3 block">
                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100 line-clamp-2">{{ $bookmark->novel->title }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $bookmark->novel->author?->name ?? 'Unknown' }}</p>
                </a>
            @empty
                <p class="text-sm text-slate-500 dark:text-slate-400 col-span-full">No bookmarked novels yet.</p>
            @endforelse
        </div>
    </article>

    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <h2 class="font-semibold text-slate-900 dark:text-white">Daily Reading Goal</h2>
        <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ $todayMinutes }} <span class="text-base font-medium text-slate-500">/ {{ $dailyGoalMinutes }} min</span></p>
        <div class="mt-4 h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
            <div class="h-2 bg-emerald-500 rounded-full" style="width: {{ $dailyGoalProgress }}%"></div>
        </div>
        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $dailyGoalProgress }}% completed today.</p>
        <p class="mt-5 text-xs text-slate-400 dark:text-slate-500">Total reading hours: {{ $totalReadingHours }}</p>
    </article>
</section>
@endsection
