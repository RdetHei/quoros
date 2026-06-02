@extends('layouts.dashboard')

@section('sidebar-nav')
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white bg-emerald-600/20 text-emerald-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        <span x-show="sidebarOpen">Dashboard</span>
    </a>
    <a href="{{ route('writer.novels.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        <span x-show="sidebarOpen">Novels</span>
    </a>
    <a href="{{ route('writer.stats') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h-2a2 2 0 00-2 2z" /></svg>
        <span x-show="sidebarOpen">Stats</span>
    </a>
    <a href="{{ route('profile.show', Auth::user()->username ?? Auth::user()->id) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
        <span x-show="sidebarOpen">Profile</span>
    </a>
    <a href="{{ route('settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924-1.756-3.35 0a1.724 1.724 0 00-1.065-2.573c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        <span x-show="sidebarOpen">Settings</span>
    </a>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-white">Writer Dashboard</h1>
            <a href="{{ route('writer.novels.create') }}" class="px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Create New Novel
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-500/20 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">Total Novels</p>
                        <p class="text-2xl font-bold text-white">{{ $totalNovels }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-violet-500/20 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">Total Chapters</p>
                        <p class="text-2xl font-bold text-white">{{ $totalChapters }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-amber-500/20 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">Total Bookmarks</p>
                        <p class="text-2xl font-bold text-white">{{ $totalBookmarks }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-500/20 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">Total Views</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($totalViews) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Novels -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">My Novels</h2>
                <a href="{{ route('writer.novels.index') }}" class="text-emerald-500 text-sm hover:text-emerald-400">View All</a>
            </div>
            @if(count($writerNovels) > 0)
                <div class="divide-y divide-slate-800">
                    @foreach($writerNovels as $novel)
                        <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 hover:bg-slate-800/30 transition-colors">
                            <div class="w-16 h-24 rounded-lg overflow-hidden bg-slate-800 flex-shrink-0">
                                @if($novel->cover_image_url)
                                    <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                                @elseif($novel->cover_image)
                                    <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-white font-semibold truncate mb-1">{{ $novel->title }}</h3>
                                <div class="flex items-center gap-3 text-sm text-slate-400 mb-2">
                                    <span>{{ $novel->chapters_count }} chapters</span>
                                    <span>•</span>
                                    <span>{{ number_format($novel->view_count) }} views</span>
                                    <span>•</span>
                                    <span>{{ $novel->bookmarks_count }} bookmarks</span>
                                </div>
                                <div class="flex gap-2 flex-wrap">
                                    @foreach($novel->genres->take(3) as $genre)
                                        <span class="px-2 py-0.5 bg-slate-800 text-slate-300 text-xs rounded-full">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex gap-2 flex-shrink-0">
                                <a href="{{ route('writer.novels.edit', $novel->slug) }}" class="px-3 py-1.5 bg-slate-800 text-slate-200 text-sm rounded-lg hover:bg-slate-700 transition-colors">Edit</a>
                                <a href="{{ route('novels.show', $novel->slug) }}" class="px-3 py-1.5 bg-slate-700 text-slate-200 text-sm rounded-lg hover:bg-slate-600 transition-colors">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                    <p class="text-slate-300 text-lg font-medium mb-2">You haven't written any novels yet</p>
                    <p class="text-slate-500 mb-6">Start your writing journey now!</p>
                    <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Create First Novel
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
