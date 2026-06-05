@extends('layouts.dashboard', [
    'title' => 'My Novels Library',
    'subtitle' => 'Manage and monitor all your published works.'
])

@section('dashboard-content')
<div class="space-y-10">
    <!-- Quick Summary Cards -->
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Works</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($summary['novel_count']) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Chapters</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($summary['chapter_count']) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Views</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($summary['total_views']) }}</p>
        </div>
        <div class="bg-indigo-600 rounded-3xl p-6 shadow-xl shadow-indigo-600/20">
            <p class="text-[10px] font-black text-white/60 uppercase tracking-[0.2em] mb-1">Bookmarks</p>
            <p class="text-2xl font-black text-white">{{ number_format($summary['total_bookmarks']) }}</p>
        </div>
    </section>

    <!-- Novel Management Table/List -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-xl font-black text-slate-900 dark:text-white">Your Stories</h2>
            <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                New Novel
            </a>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($novels as $novel)
                <div class="p-8 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all">
                    <div class="flex flex-col md:flex-row gap-8 items-center">
                        <div class="shrink-0 w-24 h-32 rounded-2xl overflow-hidden shadow-lg border border-slate-200 dark:border-slate-700">
                            @if($novel->cover_image_url)
                                <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-grow min-w-0">
                            <div class="flex items-center gap-4 mb-2">
                                <h3 class="text-xl font-black text-slate-900 dark:text-white truncate">{{ $novel->title }}</h3>
                                <span @class([
                                    'px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border',
                                    'bg-emerald-50 text-emerald-600 border-emerald-100' => $novel->status === 'ongoing',
                                    'bg-indigo-50 text-indigo-600 border-indigo-100' => $novel->status === 'complete',
                                    'bg-slate-50 text-slate-500 border-slate-200' => $novel->status === 'hiatus',
                                ])>
                                    {{ $novel->status }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-1 mb-4">{{ $novel->description }}</p>
                            
                            <div class="flex flex-wrap items-center gap-6">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Chapters</span>
                                    <span class="text-sm font-black text-slate-700 dark:text-slate-200">{{ $novel->chapters_count }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Views</span>
                                    <span class="text-sm font-black text-slate-700 dark:text-slate-200">{{ number_format($novel->view_count) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Bookmarks</span>
                                    <span class="text-sm font-black text-slate-700 dark:text-slate-200">{{ number_format($novel->bookmarks_count) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="shrink-0 flex items-center gap-3">
                            <a href="{{ route('writer.novels.chapters.create', $novel->id) }}" class="p-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Add Chapter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            </a>
                            <a href="{{ route('writer.novels.edit', $novel->id) }}" class="p-3 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-2xl transition-all shadow-sm" title="Edit Novel">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </a>
                            <a href="{{ route('novels.show', $novel->slug) }}" target="_blank" class="p-3 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-indigo-600 rounded-2xl transition-all shadow-sm" title="View Site">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center">
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">No novels found in your library.</p>
                </div>
            @endforelse
        </div>
    </div>

    @if($novels->hasPages())
        <div class="mt-8">
            {{ $novels->links() }}
        </div>
    @endif
</div>
@endsection
