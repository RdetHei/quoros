@extends('layouts.dashboard', [
    'title' => 'Workspace Overview',
    'subtitle' => 'Monitor your novel performance and reader engagement.'
])

@section('dashboard-content')
<div class="space-y-10">
    <!-- Stats Grid -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </div>
                <span class="text-xs font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded-lg">+12%</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Views Today</p>
            <p class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($viewsToday) }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Bookmarks</p>
            <p class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($newBookmarksToday) }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Avg Rating</p>
            <p class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($averageRating, 1) }}</p>
        </div>

        <div class="bg-indigo-600 rounded-[2rem] p-8 shadow-xl shadow-indigo-600/20 flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <a href="{{ route('writer.novels.create') }}" class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-white/60 mb-1">Quick Start</p>
                    <p class="text-2xl font-black text-white leading-tight">Create New Masterpiece</p>
                </div>
                <div class="mt-6 flex items-center gap-2 text-xs font-bold text-white uppercase tracking-widest">
                    Launch Novel 
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </div>
            </a>
        </div>
    </section>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        <!-- Managed Novels -->
        <div class="xl:col-span-8 space-y-8">
            <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-4">
                        <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                        Active Novels
                    </h2>
                    <a href="{{ route('writer.novels.index') }}" class="text-[10px] font-black text-indigo-600 hover:text-indigo-700 uppercase tracking-[0.2em] transition-colors">View All Works</a>
                </div>
                
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($myNovels as $novel)
                        <div class="p-8 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all">
                            <div class="flex flex-col md:flex-row gap-8">
                                <div class="shrink-0 w-28 h-40 rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-700 relative group">
                                    @if($novel->cover_image_url)
                                        <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="{{ route('novels.show', $novel->slug) }}" target="_blank" class="p-3 bg-white text-slate-900 rounded-2xl shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                        </a>
                                    </div>
                                </div>

                                <div class="flex-grow min-w-0 flex flex-col justify-between py-2">
                                    <div>
                                        <div class="flex items-center gap-4 mb-3">
                                            <h3 class="text-2xl font-black text-slate-900 dark:text-white truncate">{{ $novel->title }}</h3>
                                            <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-900/50 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                                                {{ $novel->status }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">{{ $novel->description ?: 'No description provided yet.' }}</p>
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center gap-8 mt-6">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Chapters</span>
                                            <span class="text-sm font-black text-slate-900 dark:text-white">{{ $novel->chapters_count }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rating</span>
                                            <span class="text-sm font-black text-slate-900 dark:text-white">{{ number_format($novel->reviews_avg_rating ?? 0, 1) }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Readers</span>
                                            <span class="text-sm font-black text-slate-900 dark:text-white">{{ number_format($novel->bookmarks_count) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 flex flex-row md:flex-col gap-3 justify-end">
                                    <a href="{{ route('writer.novels.chapters.create', $novel->id) }}" class="flex-1 md:flex-none px-6 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all text-center shadow-lg shadow-indigo-600/10">
                                        + Chapter
                                    </a>
                                    <a href="{{ route('writer.novels.edit', $novel->id) }}" class="flex-1 md:flex-none px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-center">
                                        Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-20 text-center">
                            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-8 text-slate-300 border-2 border-dashed border-slate-200 dark:border-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-3">No stories yet</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-10 max-w-xs mx-auto">Ready to share your imagination with the world? Start your first novel today.</p>
                            <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-indigo-600 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">
                                Create Novel
                            </a>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Sidebar Activity -->
        <div class="xl:col-span-4 space-y-8">
            <!-- Recent Feedback -->
            <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white">New Activity</h2>
                    <span class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-500 border border-slate-100 dark:border-slate-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    </span>
                </div>

                <div class="space-y-6">
                    @forelse($latestComments as $comment)
                        <div class="relative pl-6 pb-6 border-l border-slate-100 dark:border-slate-800 last:pb-0">
                            <div class="absolute left-[-5px] top-0 w-[9px] h-[9px] rounded-full bg-indigo-500"></div>
                            <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-2">{{ $comment->chapter?->novel?->title }}</p>
                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed italic mb-3">"{{ Str::limit($comment->content, 80) }}"</p>
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-black text-slate-500">
                                    {{ substr($comment->user?->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-slate-500">{{ $comment->user?->name }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center bg-slate-50/50 dark:bg-slate-800/20 rounded-3xl border-2 border-dashed border-slate-100 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No recent comments</p>
                        </div>
                    @endforelse
                </div>
                
                <a href="{{ route('writer.feedback.hub') }}" class="mt-8 block w-full py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-center text-xs font-black uppercase tracking-widest rounded-2xl hover:opacity-90 transition-all">
                    Open Feedback Hub
                </a>
            </section>

            <!-- Drafts Queue -->
            <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm">
                <h2 class="text-xl font-black text-slate-900 dark:text-white mb-8">Work in Progress</h2>
                <div class="space-y-4">
                    @forelse($draftChapters as $chapter)
                        <div class="flex items-center justify-between p-5 rounded-3xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 group hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all">
                            <div class="min-w-0">
                                <p class="text-sm font-black text-slate-900 dark:text-white truncate">{{ $chapter->title }}</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $chapter->novel?->title }}</p>
                            </div>
                            <a href="{{ route('writer.novels.chapters.edit', [$chapter->novel_id, $chapter->id]) }}" class="shrink-0 w-10 h-10 rounded-xl bg-white dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm transition-all group-hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                        </div>
                    @empty
                        <div class="py-10 text-center bg-slate-50/50 dark:bg-slate-800/20 rounded-3xl border-2 border-dashed border-slate-100 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No active drafts</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
