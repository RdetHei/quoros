@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl pb-14 pt-8">
    <div class="mb-8 rounded-[28px] border border-neutral-800 bg-[#10161d] p-6 shadow-[0_22px_60px_rgba(0,0,0,0.28)] sm:p-7">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-2 text-[10px] font-medium uppercase tracking-[0.24em] text-neutral-500">Reading</p>
                <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">Reading History</h1>
            </div>
            <span class="inline-flex w-fit items-center rounded-full border border-neutral-700 bg-neutral-900 px-3 py-1.5 text-xs font-medium text-neutral-300">
                {{ $histories->total() }} entries
            </span>
        </div>
    </div>

    <div class="overflow-hidden rounded-[28px] border border-neutral-800 bg-[#0d1218]">
        @forelse($histories as $history)
            <div @class(['border-b border-neutral-800 last:border-b-0'])>
                <div class="flex flex-col gap-4 p-4 transition-colors hover:bg-white/[0.02] sm:flex-row sm:items-center sm:p-5">
                    <a href="{{ route('novels.show', $history->novel->slug) }}" class="h-[5.5rem] w-16 shrink-0 overflow-hidden rounded-xl border border-neutral-700 bg-neutral-900 sm:h-28 sm:w-20">
                        @if($history->novel->cover_image_url)
                            <img src="{{ $history->novel->cover_image_url }}" alt="{{ $history->novel->title }}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($history->novel->cover_image)
                            <img src="{{ asset('storage/' . $history->novel->cover_image) }}" alt="{{ $history->novel->title }}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            <div class="flex h-full w-full items-center justify-center p-2">
                                <span class="text-center text-[10px] font-medium text-neutral-400 line-clamp-3">{{ $history->novel->title }}</span>
                            </div>
                        @endif
                    </a>

                    <div class="min-w-0 flex-1">
                        <div class="mb-1 flex flex-wrap items-center gap-2">
                            <a href="{{ route('novels.show', $history->novel->slug) }}" class="line-clamp-1 text-base font-semibold text-white transition-colors hover:text-neutral-200">
                                {{ $history->novel->title }}
                            </a>
                            <span class="text-xs text-neutral-500">{{ $history->updated_at->diffForHumans() }}</span>
                        </div>
                        <p class="mb-2 text-sm text-neutral-400">{{ $history->novel->author->name }}</p>
                        <p class="flex items-center gap-1.5 text-xs text-neutral-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            <span class="line-clamp-1">{{ $history->chapter->title }}</span>
                        </p>
                    </div>

                    <a href="{{ route('chapters.show', [$history->novel->slug, $history->chapter->slug]) }}" class="inline-flex shrink-0 items-center justify-center rounded-xl border border-neutral-700 bg-white px-5 py-2.5 text-sm font-semibold text-neutral-900 transition-colors hover:bg-neutral-200">
                        Continue
                    </a>
                </div>
            </div>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl border border-neutral-700 bg-neutral-900 text-neutral-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="mb-1 text-lg font-semibold text-white">No history yet</h3>
                <p class="mb-6 text-sm text-neutral-400">Start reading to see your progress here.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-xl border border-neutral-700 bg-white px-5 py-2.5 text-sm font-semibold text-neutral-900 transition-colors hover:bg-neutral-200">
                    Explore Novels
                </a>
            </div>
        @endforelse
    </div>

    @if($histories->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $histories->links() }}
        </div>
    @endif
</div>
@endsection
