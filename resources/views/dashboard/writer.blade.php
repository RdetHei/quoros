@extends('layouts.dashboard', [
    'title' => 'Author Studio',
    'subtitle' => 'Creative journey and reader insights.'
])

@section('dashboard-content')
<div class="space-y-8 pb-10">
    <!-- Muted Compact Stats Grid -->
    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-lg">+12%</span>
            </div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Impressions</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($viewsToday) }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                </div>
            </div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Fan Base</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($newBookmarksToday) }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                </div>
            </div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Rating</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($averageRating, 1) }}</p>
        </div>

        <a href="{{ route('writer.novels.create') }}" class="block bg-slate-900 dark:bg-white rounded-2xl p-5 shadow-lg hover:opacity-90 transition-all flex flex-col justify-between">
            <div>
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">New Entry</p>
                <h3 class="text-lg font-black text-white dark:text-slate-900 leading-tight">Create Novel</h3>
            </div>
            <div class="mt-4 flex items-center gap-2 text-[9px] font-black text-white dark:text-slate-900 uppercase tracking-widest">
                Launch
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </div>
        </a>
    </section>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <!-- Compact Catalog -->
        <div class="xl:col-span-8 space-y-6">
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-6 bg-slate-800 dark:bg-slate-200 rounded-full"></div>
                    <h2 class="text-lg font-black text-slate-900 dark:text-white tracking-tight uppercase">Active Catalog</h2>
                </div>
                <a href="{{ route('writer.novels.index') }}" class="text-[9px] font-black text-slate-400 hover:text-slate-900 dark:hover:text-white uppercase tracking-widest transition-colors">View All</a>
            </div>

            <div class="grid grid-cols-1 gap-3">
                @forelse($myNovels as $novel)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 transition-all">
                        <div class="flex items-center gap-5">
                            <!-- Visual -->
                            <div class="shrink-0">
                                <div class="w-16 h-24 rounded-xl overflow-hidden shadow-md border border-slate-200 dark:border-slate-700">
                                    @if($novel->cover_image_url)
                                        <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-base font-black text-slate-900 dark:text-white truncate uppercase">{{ $novel->title }}</h3>
                                    <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-500">{{ $novel->status }}</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mb-4">{{ $novel->description ?: 'No briefing provided.' }}</p>
                                
                                <div class="flex items-center gap-6">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[9px] font-black text-slate-400 uppercase">Ch:</span>
                                        <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{ $novel->chapters_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[9px] font-black text-slate-400 uppercase">Rate:</span>
                                        <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{ number_format($novel->reviews_avg_rating ?? 0, 1) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[9px] font-black text-slate-400 uppercase">Fans:</span>
                                        <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{ number_format($novel->bookmarks_count) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="shrink-0 flex gap-2">
                                <a href="{{ route('novels.show', $novel->slug) }}" target="_blank" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all" title="Lihat Novel">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('writer.novels.workspace', $novel) }}" class="p-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all" title="Buka Workspace">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No Manuscripts Yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pulse Feed -->
        <div class="xl:col-span-4 space-y-6">
            <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Recent Interaction</h2>

                <div class="space-y-6">
                    @forelse($latestComments as $comment)
                        <div class="space-y-2">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $comment->chapter?->novel?->title }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-300 italic leading-relaxed line-clamp-2">"{{ $comment->content }}"</p>
                            <p class="text-[9px] font-bold text-slate-400">{{ $comment->user?->name }} &bull; {{ $comment->created_at->diffForHumans(null, true) }}</p>
                        </div>
                    @empty
                        <p class="text-[9px] font-black text-slate-400 text-center uppercase tracking-widest py-4">Quiet Session</p>
                    @endforelse
                </div>
            </section>

            <!-- Studio Queue -->
            <section class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">WIP Queue</h2>
                
                <div class="space-y-3">
                    @forelse($draftChapters as $chapter)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                            <div class="min-w-0 pr-4">
                                <p class="text-[11px] font-black text-slate-900 dark:text-white truncate uppercase">{{ $chapter->title }}</p>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $chapter->novel?->title }}</p>
                            </div>
                            <a href="{{ route('writer.novels.chapters.edit', [$chapter->novel_id, $chapter->id]) }}" class="shrink-0 text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </a>
                        </div>
                    @empty
                        <p class="text-[9px] font-black text-slate-400 text-center uppercase tracking-widest py-4">No Drafts</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
