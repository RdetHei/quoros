@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Riwayat Bacaan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Lanjutkan dari chapter terakhir yang Anda baca.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        @forelse($histories as $history)
            <div @class(['border-b border-slate-100 dark:border-slate-800 last:border-b-0'])>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 sm:p-5 hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                    <a href="{{ route('novels.show', $history->novel->slug) }}" class="shrink-0 w-16 h-[5.5rem] sm:w-20 sm:h-28 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200 dark:ring-slate-700">
                        @if($history->novel->cover_image_url)
                            <img src="{{ $history->novel->cover_image_url }}" alt="{{ $history->novel->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($history->novel->cover_image)
                            <img src="{{ asset('storage/' . $history->novel->cover_image) }}" alt="{{ $history->novel->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            <div class="w-full h-full flex items-center justify-center p-2">
                                <span class="text-[10px] font-medium text-slate-400 text-center line-clamp-3">{{ $history->novel->title }}</span>
                            </div>
                        @endif
                    </a>

                    <div class="flex-grow min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <a href="{{ route('novels.show', $history->novel->slug) }}" class="text-base font-semibold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors line-clamp-1">
                                {{ $history->novel->title }}
                            </a>
                            <span class="text-xs text-slate-400">{{ $history->updated_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">{{ $history->novel->author->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            <span class="line-clamp-1">{{ $history->chapter->title }}</span>
                        </p>
                    </div>

                    <a href="{{ route('chapters.show', [$history->novel->slug, $history->chapter->slug]) }}" class="shrink-0 inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                        Lanjutkan
                    </a>
                </div>
            </div>
        @empty
            <div class="py-16 px-6 text-center">
                <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Belum ada riwayat</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Mulai membaca novel untuk melihat progres di sini.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                    Jelajahi Novel
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
