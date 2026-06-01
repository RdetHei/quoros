@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-1 h-10 bg-rose-600 rounded-full shrink-0"></div>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Bookmark Saya</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Koleksi novel yang kamu simpan untuk dibaca nanti.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        @forelse($bookmarks as $bookmark)
            <div @class(['border-b border-slate-100 dark:border-slate-800 last:border-b-0'])>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 sm:p-5 hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                    <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="shrink-0 w-16 h-[5.5rem] sm:w-20 sm:h-28 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200 dark:ring-slate-700">
                        @if($bookmark->novel->cover_image_url)
                            <img src="{{ $bookmark->novel->cover_image_url }}" alt="{{ $bookmark->novel->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($bookmark->novel->cover_image)
                            <img src="{{ asset('storage/' . $bookmark->novel->cover_image) }}" alt="{{ $bookmark->novel->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            <div class="w-full h-full flex items-center justify-center p-2">
                                <span class="text-[10px] font-medium text-slate-400 text-center line-clamp-3">{{ $bookmark->novel->title }}</span>
                            </div>
                        @endif
                    </a>

                    <div class="flex-grow min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="text-base font-semibold text-slate-900 dark:text-white hover:text-rose-600 dark:hover:text-rose-400 transition-colors line-clamp-1">
                                {{ $bookmark->novel->title }}
                            </a>
                            <span class="text-xs text-slate-400">{{ $bookmark->updated_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">{{ $bookmark->novel->author->name }}</p>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                            <span>{{ $bookmark->read_chapters_count }}/{{ $bookmark->total_chapters }} chapter</span>
                            <!-- Reading Progress Bar -->
                            <div class="w-24 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-600 transition-all duration-500" style="width: {{ $bookmark->progress_percentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('bookmarks.toggle', $bookmark->novel->id) }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" class="w-10 h-10 inline-flex items-center justify-center bg-rose-100 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-200 dark:hover:bg-rose-900/40 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-16 px-6 text-center">
                <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Belum ada bookmark</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Simpan novel favoritmu untuk dibaca nanti.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 text-white text-sm font-semibold rounded-xl hover:bg-rose-700 transition-colors">
                    Jelajahi Novel
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
