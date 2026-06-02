@extends('layouts.dashboard')

@php
    $dashboardTitle = 'Feedback Hub';
    $dashboardSubtitle = 'All comments and reviews across your novels in one place.';
    $dashboardBreadcrumbs = ['Dashboard', 'Writer', 'Feedback Hub'];
@endphp

@section('dashboard-content')
<section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <h2 class="font-semibold text-slate-900 dark:text-white">Latest Reviews</h2>
        <ul class="mt-4 space-y-2 text-sm">
            @forelse($reviews as $review)
                <li class="border border-slate-100 dark:border-slate-800 rounded-xl p-3">
                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ $review->novel?->title }}</p>
                    <p class="text-slate-500 dark:text-slate-400">{{ $review->user?->name ?? 'Reader' }} - {{ $review->rating }}/5</p>
                    @if($review->content)
                        <p class="mt-1 text-slate-600 dark:text-slate-300">{{ \Illuminate\Support\Str::limit($review->content, 120) }}</p>
                    @endif
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">No reviews yet.</li>
            @endforelse
        </ul>
        <div class="mt-4">{{ $reviews->links() }}</div>
    </article>

    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
        <h2 class="font-semibold text-slate-900 dark:text-white">Latest Comments</h2>
        <ul class="mt-4 space-y-2 text-sm">
            @forelse($comments as $comment)
                <li class="border border-slate-100 dark:border-slate-800 rounded-xl p-3">
                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ $comment->chapter?->novel?->title ?? 'Unknown novel' }}</p>
                    <p class="text-slate-500 dark:text-slate-400">{{ $comment->user?->name ?? 'Reader' }} on {{ $comment->chapter?->title ?? 'Unknown chapter' }}</p>
                    <p class="mt-1 text-slate-600 dark:text-slate-300">{{ \Illuminate\Support\Str::limit($comment->content, 120) }}</p>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">No comments yet.</li>
            @endforelse
        </ul>
        <div class="mt-4">{{ $comments->links() }}</div>
    </article>
</section>
@endsection
