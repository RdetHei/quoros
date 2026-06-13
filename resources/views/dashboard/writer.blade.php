@extends('layouts.dashboard', [
    'title' => 'Author Studio',
    'subtitle' => 'The unified workspace for your creative journey.'
])

@section('dashboard-content')
<div class="space-y-8 pb-10" x-data="{ 
    activeTab: (new URLSearchParams(window.location.search)).get('tab') || 'overview',
    switchTab(tab) {
        this.activeTab = tab;
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.pushState({}, '', url);
    }
}">
    <!-- 1. Quick Stats Row (Permanent) -->
    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Impressions Card -->
        <div class="bg-gradient-to-br from-slate-900/80 to-slate-950 border border-slate-800/80 rounded-3xl p-6 shadow-xl relative overflow-hidden group hover:border-indigo-500/30 transition-all duration-300">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl group-hover:bg-indigo-500/10 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="text-[10px] font-black text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2.5 py-1 rounded-lg">Today</span>
            </div>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Impressions</p>
            <p class="text-3xl font-black text-white tracking-tight">{{ number_format($viewsToday) }}</p>
        </div>

        <!-- Fan Base Card -->
        <div class="bg-gradient-to-br from-slate-900/80 to-slate-950 border border-slate-800/80 rounded-3xl p-6 shadow-xl relative overflow-hidden group hover:border-rose-500/30 transition-all duration-300">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl group-hover:bg-rose-500/10 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-rose-500/10 text-rose-400 border border-rose-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-black text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-lg">New</span>
            </div>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Added Bookmarks</p>
            <p class="text-3xl font-black text-white tracking-tight">{{ number_format($newBookmarksToday) }}</p>
        </div>

        <!-- Average Rating Card -->
        <div class="bg-gradient-to-br from-slate-900/80 to-slate-950 border border-slate-800/80 rounded-3xl p-6 shadow-xl relative overflow-hidden group hover:border-amber-500/30 transition-all duration-300">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <span class="text-[10px] font-black text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 rounded-lg">Avg</span>
            </div>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Story Rating</p>
            <p class="text-3xl font-black text-white tracking-tight">{{ number_format($averageRating, 1) }} <span class="text-xs text-slate-400 font-bold">/ 5.0</span></p>
        </div>

        <!-- Create Entry Link Card -->
        <a href="{{ route('writer.novels.create') }}" class="group bg-indigo-600 rounded-3xl p-6 shadow-xl shadow-indigo-600/10 hover:shadow-indigo-600/25 transition-all duration-300 flex flex-col justify-between relative overflow-hidden border border-indigo-500/30">
            <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-all duration-300"></div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-1">New Project</p>
                <h3 class="text-xl font-black text-white leading-tight">Create Novel</h3>
            </div>
            <div class="mt-6 flex items-center gap-2 text-[10px] font-black text-white uppercase tracking-widest">
                Get Started
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </div>
        </a>
    </section>

    <!-- 2. Unified Tab Navigation -->
    <div class="flex items-center gap-1.5 p-1.5 bg-slate-100 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-2xl w-fit">
        <button @click="switchTab('overview')" :class="activeTab === 'overview' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-5 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
            Overview
        </button>
        <button @click="switchTab('library')" :class="activeTab === 'library' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-5 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
            Library
        </button>
        <button @click="switchTab('analytics')" :class="activeTab === 'analytics' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-5 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
            Analytics
        </button>
        <button @click="switchTab('community')" :class="activeTab === 'community' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-5 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
            Community
        </button>
    </div>

    <!-- 3. Tab Contents -->
    
    <!-- Tab: Overview -->
    <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        <!-- Left: Active Manuscripts & WIP -->
        <div class="xl:col-span-8 space-y-8">
            <section class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">Top Performing</h2>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    @forelse($myNovels->take(3) as $novel)
                        <div class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 border border-slate-800/60 rounded-3xl p-5 hover:border-slate-700/60 transition-all duration-300 group">
                            <div class="flex items-center gap-6">
                                <div class="shrink-0 w-14 h-20 rounded-xl overflow-hidden border border-slate-800">
                                    @if($novel->cover_image_url)
                                        <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow min-w-0">
                                    <h3 class="text-sm font-black text-white truncate group-hover:text-indigo-400 transition-colors">{{ $novel->title }}</h3>
                                    <div class="flex items-center gap-4 mt-2 text-slate-400">
                                        <span class="text-[9px] font-black uppercase tracking-widest"><span class="text-white">{{ number_format($novel->view_count) }}</span> Views</span>
                                        <span class="text-[9px] font-black uppercase tracking-widest"><span class="text-white">{{ number_format($novel->bookmarks_count) }}</span> Fans</span>
                                    </div>
                                </div>
                                <a href="{{ route('writer.novels.workspace', $novel) }}" class="p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl transition-all shadow-lg shadow-indigo-600/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center border-2 border-dashed border-slate-800 rounded-3xl text-slate-500 text-xs font-black uppercase tracking-widest">No manuscripts yet.</div>
                    @endforelse
                </div>
            </section>

            <section class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 border border-slate-800/60 rounded-[2rem] p-6 shadow-xl">
                <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Work in Progress</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($draftChapters as $chapter)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-950/40 border border-slate-800/40 hover:border-slate-800 transition-colors">
                            <div class="min-w-0 pr-4">
                                <p class="text-xs font-bold text-white truncate leading-tight mb-0.5">{{ $chapter->title }}</p>
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest truncate">{{ $chapter->novel?->title }}</p>
                            </div>
                            <a href="{{ route('writer.novels.chapters.edit', [$chapter->novel_id, $chapter->id]) }}" class="shrink-0 p-2 rounded-xl bg-slate-800/50 hover:bg-indigo-600 text-slate-400 hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </a>
                        </div>
                    @empty
                        <div class="md:col-span-2 py-6 text-center text-slate-600 text-[9px] font-black uppercase tracking-widest">No drafts currently.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Right: Activity Feed -->
        <div class="xl:col-span-4 space-y-8">
            <section class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 border border-slate-800/60 rounded-[2rem] p-6 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Latest Interactions</h2>
                    <button @click="switchTab('community')" class="text-[8px] font-black text-indigo-400 uppercase tracking-widest hover:text-indigo-300">View All</button>
                </div>
                <div class="space-y-4">
                    @foreach($latestComments->take(4) as $comment)
                        <div class="p-4 bg-slate-950/40 rounded-2xl border border-slate-800/40 relative group hover:border-slate-800 transition-colors">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[8px] font-black text-indigo-400 bg-indigo-500/10 px-1.5 py-0.5 rounded border border-indigo-500/20 uppercase tracking-widest">Comment</span>
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest truncate">{{ $comment->chapter?->novel?->title }}</p>
                            </div>
                            <p class="text-xs text-slate-300 italic leading-relaxed line-clamp-2 mb-2">"{{ $comment->content }}"</p>
                            <div class="flex items-center justify-between text-[8px] font-bold text-slate-500">
                                <span>{{ $comment->user?->name }}</span>
                                <span>{{ $comment->created_at->diffForHumans(null, true) }}</span>
                            </div>
                        </div>
                    @endforeach
                    @foreach($latestReviews->take(4) as $review)
                        <div class="p-4 bg-slate-950/40 rounded-2xl border border-slate-800/40 relative group hover:border-slate-800 transition-colors">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[8px] font-black text-amber-400 bg-amber-500/10 px-1.5 py-0.5 rounded border border-amber-500/20 uppercase tracking-widest">Review</span>
                                <div class="flex items-center gap-0.5 text-amber-500 scale-75">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $i < $review->rating ? 'fill-current' : 'text-slate-700' }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed line-clamp-2 mb-2">"{{ $review->content ?: 'No written feedback.' }}"</p>
                            <div class="flex items-center justify-between text-[8px] font-bold text-slate-500">
                                <span>{{ $review->user?->name }}</span>
                                <span>{{ $review->created_at->diffForHumans(null, true) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <!-- Tab: Library (Full Catalog) -->
    <div x-show="activeTab === 'library'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($myNovels as $novel)
                <div class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 border border-slate-800/60 rounded-[2rem] p-6 hover:border-slate-700/60 transition-all duration-300 group flex flex-col justify-between">
                    <div class="flex gap-4 mb-4">
                        <div class="shrink-0 w-16 h-24 rounded-xl overflow-hidden border border-slate-800">
                            @if($novel->cover_image_url)
                                <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base font-black text-white truncate group-hover:text-indigo-400 transition-colors">{{ $novel->title }}</h3>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mt-1">{{ $novel->status }}</p>
                            <div class="flex items-center gap-3 mt-3">
                                <div class="text-center">
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Chapters</p>
                                    <p class="text-xs font-black text-white">{{ $novel->chapters_count }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Fans</p>
                                    <p class="text-xs font-black text-white">{{ number_format($novel->bookmarks_count) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pt-4 border-t border-slate-800/60">
                        <a href="{{ route('writer.novels.workspace', $novel) }}" class="flex-grow py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl text-center transition-all shadow-lg shadow-indigo-600/10">Workspace</a>
                        <a href="{{ route('writer.novels.edit', $novel) }}" class="p-2.5 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-xl transition-all border border-slate-700/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-800 rounded-3xl">
                    <p class="text-slate-500 font-black uppercase tracking-widest text-xs">No manuscripts in your catalog.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Tab: Analytics (Charts) -->
    <div x-show="activeTab === 'analytics'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-100 dark:bg-slate-950/40 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800">
            <div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">Performance Metrics</h3>
                <p class="text-[10px] text-slate-500 uppercase font-bold mt-1">Last 30 days aggregation</p>
            </div>
            <form action="{{ route('dashboard') }}" method="GET" id="analyticsFilterForm" class="w-full md:w-72">
                <input type="hidden" name="tab" value="analytics">
                <select name="novel_id" onchange="document.getElementById('analyticsFilterForm').submit()" class="w-full px-5 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer">
                    <option value="">All Managed Works</option>
                    @foreach($allNovels as $novel)
                        <option value="{{ $novel->id }}" {{ $selectedNovelId == $novel->id ? 'selected' : '' }}>{{ $novel->title }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 rounded-3xl p-6 border border-slate-800/60 shadow-xl">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Lifetime Views</p>
                <p class="text-3xl font-black text-white">{{ number_format($totalViews) }}</p>
            </div>
            <div class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 rounded-3xl p-6 border border-slate-800/60 shadow-xl">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Bookmarks</p>
                <p class="text-3xl font-black text-white">{{ number_format($totalBookmarks) }}</p>
            </div>
            <div class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 rounded-3xl p-6 border border-slate-800/60 shadow-xl">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Reviews</p>
                <p class="text-3xl font-black text-white">{{ number_format($totalReviews) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 bg-gradient-to-br from-slate-900/60 to-slate-950/80 rounded-[2rem] p-8 border border-slate-800/60 shadow-xl">
                <h3 class="text-sm font-black text-white uppercase tracking-wider mb-6">Engagement Trend (Last 30 Days)</h3>
                <div class="h-[300px]">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
            <div class="lg:col-span-4 bg-gradient-to-br from-slate-900/60 to-slate-950/80 rounded-[2rem] p-8 border border-slate-800/60 shadow-xl">
                <h3 class="text-sm font-black text-white uppercase tracking-wider mb-6">Interaction Ratio</h3>
                <div class="h-[250px] relative">
                    <canvas id="interactionChart"></canvas>
                </div>
                <div class="mt-8 space-y-3">
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-950/50 border border-slate-800/60">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bookmarks</span>
                        <span class="text-xs font-black text-white">{{ number_format($totalBookmarks) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-950/50 border border-slate-800/60">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Reviews</span>
                        <span class="text-xs font-black text-white">{{ number_format($totalReviews) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Community (Feedback) -->
    <div x-show="activeTab === 'community'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Reviews -->
        <div class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 border border-slate-800/60 rounded-[2rem] overflow-hidden shadow-xl flex flex-col h-full">
            <div class="p-6 border-b border-slate-800/60 bg-slate-950/30 flex items-center justify-between">
                <h2 class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Latest Reviews</h2>
            </div>
            <div class="divide-y divide-slate-800/60">
                @forelse($latestReviews as $review)
                    <div class="p-6 hover:bg-slate-950/30 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-[10px] font-black text-slate-400 border border-slate-700">
                                    {{ substr($review->user?->name ?? 'R', 0, 1) }}
                                </div>
                                <span class="text-xs font-black text-white">{{ $review->user?->name }}</span>
                            </div>
                            <div class="flex items-center gap-0.5 text-amber-500 scale-90">
                                @for($i = 0; $i < 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $i < $review->rating ? 'fill-current' : 'text-slate-700' }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-[8px] font-black text-indigo-400 uppercase tracking-widest mb-2">{{ $review->novel?->title }}</p>
                        <p class="text-xs text-slate-400 leading-relaxed italic">"{{ $review->content ?: 'No written feedback.' }}"</p>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-600 text-[9px] font-black uppercase tracking-widest">No reviews found.</div>
                @endforelse
            </div>
        </div>

        <!-- Comments -->
        <div class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 border border-slate-800/60 rounded-[2rem] overflow-hidden shadow-xl flex flex-col h-full">
            <div class="p-6 border-b border-slate-800/60 bg-slate-950/30 flex items-center justify-between">
                <h2 class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Latest Comments</h2>
            </div>
            <div class="divide-y divide-slate-800/60">
                @forelse($latestComments as $comment)
                    <div class="p-6 hover:bg-slate-950/30 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-[10px] font-black text-slate-400 border border-slate-700">
                                    {{ substr($comment->user?->name ?? 'R', 0, 1) }}
                                </div>
                                <span class="text-xs font-black text-white">{{ $comment->user?->name }}</span>
                            </div>
                            <span class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-[8px] font-black text-indigo-400 uppercase tracking-widest mb-2">{{ $comment->chapter?->novel?->title }} / {{ $comment->chapter?->title }}</p>
                        <p class="text-xs text-slate-400 leading-relaxed italic">"{{ $comment->content }}"</p>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-600 text-[9px] font-black uppercase tracking-widest">No comments found.</div>
                @endforelse
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
    const gridColor = 'rgba(148, 163, 184, 0.08)';
    const tickColor = '#64748b';

    const tooltipStyles = {
        backgroundColor: 'rgba(15, 23, 42, 0.95)',
        titleFont: { size: 11, weight: 'bold' },
        bodyFont: { size: 11 },
        padding: 10,
        cornerRadius: 12,
        borderColor: 'rgba(255,255,255,0.06)',
        borderWidth: 1,
        displayColors: true,
        usePointStyle: true,
    };

    // 1. Line Chart: Growth
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Bookmarks',
                    data: bookmarkData,
                    borderColor: '#f43f5e',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: true,
                    backgroundColor: 'rgba(244, 63, 94, 0.05)',
                    tension: 0.35,
                },
                {
                    label: 'Reviews',
                    data: reviewData,
                    borderColor: '#f59e0b',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: true,
                    backgroundColor: 'rgba(245, 158, 11, 0.05)',
                    tension: 0.35,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: { legend: { display: false }, tooltip: tooltipStyles },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor, drawBorder: false }, ticks: { font: { size: 10, weight: 'bold' }, color: tickColor } },
                x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' }, color: tickColor, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
            },
        },
    });

    // 2. Doughnut Chart: Ratio
    new Chart(document.getElementById('interactionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Bookmarks', 'Reviews'],
            datasets: [{
                data: [{{ $totalBookmarks }}, {{ $totalReviews }}],
                backgroundColor: ['#f43f5e', '#f59e0b'],
                borderWidth: 4,
                borderColor: '#0f172a',
                hoverOffset: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '78%',
            plugins: { legend: { display: false }, tooltip: tooltipStyles },
        },
    });
</script>
@endpush
@endsection
