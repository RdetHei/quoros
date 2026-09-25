@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl pb-14 pt-8">
    <div class="mb-8 rounded-[28px] border border-neutral-800 bg-[#10161d] p-6 shadow-[0_22px_60px_rgba(0,0,0,0.28)] sm:p-7">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-2 text-[10px] font-medium uppercase tracking-[0.24em] text-neutral-500">Library</p>
                <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">My Bookmarks</h1>
            </div>
            <span class="inline-flex w-fit items-center rounded-full border border-neutral-700 bg-neutral-900 px-3 py-1.5 text-xs font-medium text-neutral-300">
                {{ $bookmarks->total() }} saved
            </span>
        </div>
    </div>

    <div class="overflow-hidden rounded-[28px] border border-neutral-800 bg-[#0d1218]">
        @forelse($bookmarks as $bookmark)
            <div @class(['border-b border-neutral-800 last:border-b-0'])>
                <div class="flex flex-col gap-4 p-4 transition-colors hover:bg-white/[0.02] sm:flex-row sm:items-center sm:p-5">
                    <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="h-[5.5rem] w-16 shrink-0 overflow-hidden rounded-xl border border-neutral-700 bg-neutral-900 sm:h-28 sm:w-20">
                        @if($bookmark->novel->cover_image_url)
                            <img src="{{ $bookmark->novel->cover_image_url }}" alt="{{ $bookmark->novel->title }}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($bookmark->novel->cover_image)
                            <img src="{{ asset('storage/' . $bookmark->novel->cover_image) }}" alt="{{ $bookmark->novel->title }}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            <div class="flex h-full w-full items-center justify-center p-2">
                                <span class="text-center text-[10px] font-medium text-neutral-400 line-clamp-3">{{ $bookmark->novel->title }}</span>
                            </div>
                        @endif
                    </a>

                    <div class="min-w-0 flex-1">
                        <div class="mb-1 flex flex-wrap items-center gap-2">
                            <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="line-clamp-1 text-base font-semibold text-white transition-colors hover:text-neutral-200">
                                {{ $bookmark->novel->title }}
                            </a>
                            <span class="text-xs text-neutral-500">{{ $bookmark->updated_at->diffForHumans() }}</span>
                        </div>
                        <p class="mb-2 text-sm text-neutral-400">{{ $bookmark->novel->author->name }}</p>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-neutral-500">
                            <span>{{ $bookmark->read_chapters_count }}/{{ $bookmark->total_chapters }} chapter</span>
                            <div class="h-1.5 w-24 overflow-hidden rounded-full bg-neutral-800">
                                <div class="h-full bg-neutral-300 transition-all duration-500" style="width: {{ $bookmark->progress_percentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('bookmarks.toggle', $bookmark->novel->id) }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-neutral-700 bg-neutral-900 text-neutral-300 transition-colors hover:border-rose-500/60 hover:bg-rose-500/10 hover:text-rose-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl border border-neutral-700 bg-neutral-900 text-neutral-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </div>
                <h3 class="mb-1 text-lg font-semibold text-white">No bookmarks yet</h3>
                <p class="mb-6 text-sm text-neutral-400">Save your favorite novels to read later.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-xl border border-neutral-700 bg-white px-5 py-2.5 text-sm font-semibold text-neutral-900 transition-colors hover:bg-neutral-200">
                    Explore Novels
                </a>
            </div>
        @endforelse
    </div>

    @if($bookmarks->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $bookmarks->links() }}
        </div>
    @endif
</div>
@endsection
