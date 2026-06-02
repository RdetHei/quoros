@extends('layouts.dashboard')

@php
    $dashboardTitle = 'Creative Command Center';
    $dashboardSubtitle = 'Track your performance and reader response in one place.';
    $dashboardBreadcrumbs = ['Dashboard', 'Writer'];
@endphp

@section('dashboard-content')
<section class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">Views Today</p>
        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($viewsToday) }}</p>
    </article>
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">New Bookmarks Today</p>
        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($newBookmarksToday) }}</p>
    </article>
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">Average Rating</p>
        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($averageRating, 1) }}</p>
    </article>
</section>

<section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-slate-900 dark:text-white">Latest Reviews</h2>
            <a href="{{ route('writer.feedback.hub') }}" class="text-sm text-indigo-600 hover:underline">Open Hub</a>
        </div>
        <ul class="mt-4 space-y-3 text-sm">
            @forelse($latestReviews as $review)
                <li class="border border-slate-100 dark:border-slate-800 rounded-xl p-3">
                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ $review->novel?->title }}</p>
                    <p class="text-slate-500 dark:text-slate-400">by {{ $review->user?->name ?? 'Reader' }} - rating {{ $review->rating }}/5</p>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">No review activity yet.</li>
            @endforelse
        </ul>
    </article>

    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <h2 class="font-semibold text-slate-900 dark:text-white">Latest Comments</h2>
        <ul class="mt-4 space-y-3 text-sm">
            @forelse($latestComments as $comment)
                <li class="border border-slate-100 dark:border-slate-800 rounded-xl p-3">
                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ $comment->chapter?->novel?->title }}</p>
                    <p class="text-slate-500 dark:text-slate-400">{{ $comment->user?->name ?? 'Reader' }}: {{ \Illuminate\Support\Str::limit($comment->content, 90) }}</p>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">No comment activity yet.</li>
            @endforelse
        </ul>
    </article>
</section>

<section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <h2 class="font-semibold text-slate-900 dark:text-white">Draft & Scheduled Chapters</h2>
        <ul class="mt-4 space-y-2 text-sm">
            @forelse($draftChapters as $chapter)
                <li class="flex items-center justify-between border border-slate-100 dark:border-slate-800 rounded-xl px-3 py-2">
                    <span class="text-slate-700 dark:text-slate-200">{{ $chapter->title }}</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 capitalize">{{ $chapter->status }}</span>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">No draft/scheduled chapters found.</li>
            @endforelse
        </ul>
    </article>

    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-slate-900 dark:text-white">Inspiration & Tips</h2>
            <a href="{{ route('guides.index') }}" class="text-sm text-indigo-600 hover:underline">View Guides</a>
        </div>
        <ul class="mt-4 space-y-3 text-sm">
            @forelse($writerTips as $tip)
                <li class="border border-slate-100 dark:border-slate-800 rounded-xl p-3">
                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ $tip->title }}</p>
                    <p class="text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Str::limit($tip->content, 90) }}</p>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">No writer tip found yet. Check guides for curated resources.</li>
            @endforelse
        </ul>
    </article>
</section>
@endsection
