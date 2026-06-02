@extends('layouts.dashboard')

@section('sidebar-nav')
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white bg-emerald-600/20 text-emerald-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        <span x-show="sidebarOpen">Dashboard</span>
    </a>
    <a href="{{ route('bookmarks.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
        <span x-show="sidebarOpen">Bookmarks</span>
    </a>
    <a href="{{ route('history.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span x-show="sidebarOpen">History</span>
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
        <h1 class="text-2xl font-bold text-white mb-8">Dashboard</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-500/20 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">Total Reading Time</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($totalReadingHours, 1) }}h</p>
                    </div>
                </div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-violet-500/20 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">Bookmarked Novels</p>
                        <p class="text-2xl font-bold text-white">{{ count($bookmarks) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-amber-500/20 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">Quoros Points</p>
                        <p class="text-2xl font-bold text-white">{{ $userPoints }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Continue Reading -->
        @if($lastRead)
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-white mb-4">Continue Reading</h2>
                <a href="{{ route('chapters.show', [$lastRead->novel->slug, $lastRead->chapter->slug]) }}" class="block bg-gradient-to-r from-indigo-900 to-violet-900 rounded-2xl p-6 border border-indigo-800 hover:border-indigo-700 transition-all">
                    <div class="flex flex-col md:flex-row gap-6 items-center">
                        @if($lastRead->novel->cover_image_url)
                            <img src="{{ $lastRead->novel->cover_image_url }}" class="w-24 h-36 rounded-lg object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($lastRead->novel->cover_image)
                            <img src="{{ asset('storage/' . $lastRead->novel->cover_image) }}" class="w-24 h-36 rounded-lg object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        @endif
                        <div class="flex-1 text-center md:text-left">
                            <p class="text-indigo-300 text-sm mb-1">{{ $lastRead->novel->title }}</p>
                            <h3 class="text-xl font-bold text-white mb-2">{{ $lastRead->chapter->title }}</h3>
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-3">
                                <div class="w-40 h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500" style="width: {{ $lastRead->progress }}%"></div>
                                </div>
                                <span class="text-xs text-slate-400">{{ $lastRead->progress }}%</span>
                            </div>
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 rounded-full text-sm font-medium">
                                Continue Reading
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        <!-- Recent Bookmarks -->
        @if(count($bookmarks) > 0)
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-white">Recent Bookmarks</h2>
                    <a href="{{ route('bookmarks.index') }}" class="text-emerald-500 text-sm hover:text-emerald-400">View All</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($bookmarks->take(5) as $bookmark)
                        <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="group">
                            <div class="aspect-[3/4] rounded-lg overflow-hidden bg-slate-800 mb-2 relative">
                                @if($bookmark->novel->cover_image_url)
                                    <img src="{{ $bookmark->novel->cover_image_url }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                                @elseif($bookmark->novel->cover_image)
                                    <img src="{{ asset('storage/' . $bookmark->novel->cover_image) }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <h4 class="text-sm font-medium text-slate-200 group-hover:text-white truncate">{{ $bookmark->novel->title }}</h4>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recommendations -->
        <div>
            <h2 class="text-lg font-semibold text-white mb-4">Recommendations For You</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recommendations as $novel)
                    <a href="{{ route('novels.show', $novel->slug) }}" class="bg-slate-900 border border-slate-800 rounded-xl p-4 hover:border-slate-700 transition-all flex gap-4">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" class="w-20 h-28 rounded-lg object-cover flex-shrink-0" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-20 h-28 rounded-lg object-cover flex-shrink-0" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            <div class="w-20 h-28 rounded-lg bg-slate-800 flex-shrink-0 flex items-center justify-center">
                                <span class="text-slate-500 text-xs">{{ $novel->title }}</span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-white truncate mb-1">{{ $novel->title }}</h4>
                            <p class="text-sm text-slate-400 truncate mb-2">{{ $novel->author->name }}</p>
                            <div class="flex items-center gap-2">
                                @if($novel->rating_avg > 0)
                                    <div class="flex items-center gap-1 text-amber-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8-2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        <span class="text-sm font-medium">{{ number_format($novel->rating_avg, 1) }}</span>
                                    </div>
                                @endif
                                <span class="text-sm text-slate-500">{{ $novel->chapters->count() }} chapters</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
