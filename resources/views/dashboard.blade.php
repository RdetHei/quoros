@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ activeTab: '{{ request()->get('tab', 'bookmarks') }}' }">
    <!-- Clean Header Section -->
    <div class="relative mb-6 md:mb-8 p-5 md:p-8 rounded-2xl md:rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-slate-50 dark:bg-slate-900/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6 md:gap-8">
            <div class="flex items-center gap-4 md:gap-6">
                <div class="relative">
                    <div class="w-16 h-16 md:w-24 md:h-24 rounded-2xl overflow-hidden ring-4 ring-slate-50 dark:ring-slate-800 shadow-md">
                        @if($user->profile_photo_url)
                            <img src="{{ $user->profile_photo_url }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xl md:text-2xl font-bold text-slate-900 dark:text-white">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 md:w-6 md:h-6 bg-emerald-500 rounded-full border-[3px] md:border-4 border-white dark:border-slate-900 shadow-sm"></div>
                </div>

                <div class="min-w-0">
                    <h1 class="text-xl md:text-3xl font-bold text-slate-900 dark:text-white mb-0.5 md:mb-1 truncate">
                        Hi, {{ $user->name }}
                    </h1>
                    <p class="text-slate-50 dark:text-slate-400 text-[11px] md:text-sm font-medium truncate">
                        {{ $user->role === 'admin' ? 'Administrator' : ($user->role === 'writer' ? 'Writer' : 'Reader') }} • Since {{ $user->created_at->format('M Y') }}
                    </p>
                </div>
            </div>

            <div class="flex gap-3 md:gap-4">
                <div class="flex-1 md:flex-none px-4 md:px-6 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl md:rounded-2xl border border-slate-100 dark:border-slate-700/50 text-center md:text-left">
                    <p class="text-[9px] md:text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5 md:mb-1">Reading Hours</p>
                    <p class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">{{ $totalReadingHours }}<span class="text-xs ml-0.5 text-slate-400 font-medium">h</span></p>
                </div>
                <div class="flex-1 md:flex-none px-4 md:px-6 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl md:rounded-2xl border border-slate-100 dark:border-slate-700/50 text-center md:text-left">
                    <p class="text-[9px] md:text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5 md:mb-1">Points</p>
                    <p class="text-lg md:text-xl font-bold text-amber-500">{{ $userPoints }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-3 space-y-6 lg:sticky lg:top-24 self-start">
            <div class="bg-white dark:bg-slate-900 rounded-2xl md:rounded-3xl p-2 md:p-4 border border-slate-200 dark:border-slate-800 shadow-sm overflow-x-auto no-scrollbar">
                <nav class="flex lg:flex-col gap-1 min-w-max lg:min-w-0">
                    <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <span>My Profile</span>
                    </a>

                    <div class="h-px bg-slate-100 dark:bg-slate-800 my-1 hidden lg:block"></div>

                    <button @click="activeTab = 'bookmarks'" 
                        :class="activeTab === 'bookmarks' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white'" 
                        class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                        <span>Bookmarks</span>
                    </button>

                    <button @click="activeTab = 'history'" 
                        :class="activeTab === 'history' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white'" 
                        class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>History</span>
                    </button>

                    <button @click="activeTab = 'recommendations'" 
                        :class="activeTab === 'recommendations' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white'" 
                        class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        <span>Recommendations</span>
                    </button>

                    <a href="{{ route('settings') }}"
                        class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Settings</span>
                    </a>

                    @if($user->role === 'user')
                    <button @click="activeTab = 'become_writer'" 
                        :class="activeTab === 'become_writer' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white'" 
                        class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        <span>Writer</span>
                    </button>
                    @endif
                </nav>

                @if($user->role === 'admin' || $user->role === 'writer')
                <div class="mt-4 md:mt-6 pt-4 md:pt-6 border-t border-slate-100 dark:border-slate-800 hidden lg:block">
                    <p class="px-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">Contributor</p>
                    <div class="space-y-1">
                        @if($user->role === 'admin')
                            <a href="{{ route('admin.carousel.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                                <span>Carousel</span>
                            </a>
                        @endif
                        <a href="{{ route('writer.stats') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 012 2h2a2 2 0 012-2" /></svg>
                            <span>Writer Dashboard</span>
                        </a>
                        <a href="{{ route('writer.novels.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            <span>My Novels</span>
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Daily Goal Card -->
            @if($user->role === 'user')
            <div class="bg-white dark:bg-slate-900 rounded-2xl md:rounded-3xl p-5 md:p-6 border border-slate-200 dark:border-slate-800 shadow-sm hidden md:block">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Daily Goal</h3>
                    </div>
                    <div class="flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-900/20 rounded-full">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Active</span>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex items-baseline gap-1 mb-1">
                        <span class="text-4xl font-bold text-slate-900 dark:text-white">45</span>
                        <span class="text-sm font-medium text-slate-400">/ 60 min</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">15 more minutes to reach your goal!</p>
                </div>

                <div class="space-y-4">
                    <div class="relative h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="absolute top-0 left-0 h-full bg-emerald-600 rounded-full transition-all duration-500" style="width: 75%"></div>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-3">
                            <div class="text-xl">🔥</div>
                            <div>
                                <p class="text-xs font-bold text-slate-900 dark:text-white">5 Day Streak!</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">Don't let the fire go out.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-9 space-y-8 md:space-y-12">
            <!-- Announcement Board -->
            @if($announcements->count() > 0)
            <div x-data="{ current: 0, total: {{ $announcements->count() }} }" class="relative">
                @foreach($announcements as $index => $announcement)
                <section x-show="current === {{ $index }}" class="relative bg-slate-900 dark:bg-slate-800 rounded-2xl md:rounded-3xl p-5 md:p-8 overflow-hidden shadow-lg shadow-black/20">
                    
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-slate-400/20 rounded-full -ml-12 -mb-12 blur-xl"></div>
                    
                    <div class="relative flex flex-col md:flex-row items-center gap-4 md:gap-6">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-white/20 backdrop-blur-md rounded-xl md:rounded-2xl flex items-center justify-center text-white shrink-0">
                            @if($announcement->type === 'warning')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            @elseif($announcement->type === 'success')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                            @endif
                        </div>
                        <div class="text-center md:text-left flex-grow">
                            <h3 class="text-base md:text-xl font-bold text-white mb-1">{{ $announcement->title }}</h3>
                            <p class="text-indigo-100 text-[10px] md:text-sm leading-relaxed line-clamp-2 md:line-clamp-none">
                                {{ $announcement->content }}
                            </p>
                        </div>
                        @if($announcement->link)
                        <div class="shrink-0 w-full md:w-auto">
                            <a href="{{ $announcement->link }}" class="inline-flex w-full md:w-auto justify-center px-5 py-2 md:py-2.5 bg-white text-indigo-600 font-bold rounded-xl text-xs md:text-sm hover:bg-indigo-50 transition-colors">
                                View Details
                            </a>
                        </div>
                        @endif
                    </div>
                </section>
                @endforeach

                @if($announcements->count() > 1)
                <div class="absolute -bottom-5 md:-bottom-6 left-1/2 -translate-x-1/2 flex gap-1.5 md:gap-2">
                    <template x-for="i in total">
                        <button @click="current = i-1" 
                            class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full transition-all duration-300"
                            :class="current === i-1 ? 'bg-indigo-600 w-3 md:w-4' : 'bg-slate-300 dark:bg-slate-700'">
                        </button>
                    </template>
                </div>
                @endif
            </div>
            @endif

            @if($lastRead)
            <section class="bg-slate-900 dark:bg-indigo-950 rounded-2xl md:rounded-3xl overflow-hidden shadow-sm">
                <div class="flex flex-col md:flex-row items-center gap-5 md:gap-8 p-5 md:p-10">
                    <div class="w-24 md:w-40 flex-shrink-0 rounded-xl md:rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('storage/' . $lastRead->novel->cover_image) }}" class="w-full h-full object-cover aspect-[3/4]" onerror="this.onerror=null; this.src='/error.png'">
                    </div>
                    
                    <div class="flex-grow space-y-4 md:space-y-6 text-center md:text-left w-full">
                        <div>
                            <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-indigo-500/20 rounded-full text-indigo-300 text-[9px] md:text-[10px] font-bold uppercase tracking-wider mb-2 md:mb-4">
                                <span class="flex h-1.5 w-1.5 md:h-2 md:w-2 rounded-full bg-indigo-500"></span>
                                Currently Reading
                            </div>
                            <h2 class="text-lg md:text-4xl font-bold text-white mb-1 md:mb-2 line-clamp-1 md:line-clamp-2">{{ $lastRead->novel->title }}</h2>
                            <p class="text-slate-400 text-[10px] md:text-sm font-medium">
                                Last: <span class="text-white font-bold">{{ $lastRead->chapter->title }}</span>
                            </p>
                        </div>

                        <div class="max-w-xs mx-auto md:mx-0">
                            <div class="flex justify-between items-end mb-1.5 md:mb-2">
                                <p class="text-[8px] md:text-[10px] font-bold text-slate-500 uppercase tracking-wider">Progress</p>
                                <span class="text-indigo-400 text-[10px] md:text-sm font-bold">{{ $lastRead->progress }}%</span>
                            </div>
                            <div class="w-full h-1 md:h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $lastRead->progress }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('chapters.show', [$lastRead->novel->slug, $lastRead->chapter->slug]) }}" class="inline-flex w-full md:w-auto justify-center items-center gap-2 md:gap-3 px-6 md:px-8 py-2.5 md:py-3 bg-white text-slate-900 font-bold rounded-xl hover:bg-indigo-50 transition-colors text-xs md:text-base">
                            Continue Reading
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 md:h-5 md:w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>
            </section>
            @endif

            <!-- Tab Contents -->
            <div class="min-h-[400px] md:min-h-[500px]">
                <!-- Bookmark Tab -->
                <div x-show="activeTab === 'bookmarks'" class="space-y-5 md:space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">My Bookmarks</h3>
                            <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400">Collection of novels you follow.</p>
                        </div>
                        <span class="px-2.5 md:px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full text-[9px] md:text-xs font-bold text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                            {{ count($bookmarks) }} Novels
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                        @forelse($bookmarks as $bookmark)
                            <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="group block">
                                <div class="relative aspect-[3/4] rounded-xl md:rounded-2xl overflow-hidden shadow-sm transition-shadow group-hover:shadow-md">
                                    @if($bookmark->novel->cover_image_url)
                                        <img src="{{ $bookmark->novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($bookmark->novel->cover_image)
                                        <img src="{{ asset('storage/' . $bookmark->novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @else
                                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2 text-center">
                                            <span class="text-[10px] text-slate-400 font-bold">{{ $bookmark->novel->title }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-80"></div>
                                    <div class="absolute inset-0 flex flex-col justify-end p-3 md:p-4">
                                        <p class="text-[8px] md:text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-0.5 md:mb-1">{{ $bookmark->novel->author->name }}</p>
                                        <h4 class="text-white font-bold text-xs md:text-sm line-clamp-2 leading-tight">{{ $bookmark->novel->title }}</h4>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full py-12 md:py-20 text-center bg-slate-50 dark:bg-slate-800/30 rounded-2xl md:rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-white dark:bg-slate-800 rounded-xl md:rounded-2xl flex items-center justify-center mx-auto text-slate-300 dark:text-slate-600 mb-3 md:mb-4 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                                </div>
                                <h4 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-1">No Bookmarks Yet</h4>
                                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-5 md:mb-6">Start exploring interesting novels and save them here!</p>
                                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 md:px-6 py-2.5 md:py-3 bg-indigo-600 text-white text-xs md:text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                                    Search Novels
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 md:h-4 md:w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- History Tab -->
                <div x-show="activeTab === 'history'" class="space-y-5 md:space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">Reading History</h3>
                            <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400">Continue your interrupted adventure.</p>
                        </div>
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-wider">Last Read</span>
                    </div>

                    <div class="space-y-3 md:space-y-4">
                        @forelse($histories as $history)
                            <div class="group bg-white dark:bg-slate-900 p-3 md:p-4 rounded-xl md:rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-row items-center gap-4 md:gap-6 transition-all duration-300 hover:border-indigo-500/30 hover:shadow-sm">
                                <div class="w-16 h-24 md:w-24 md:h-32 flex-shrink-0 rounded-lg md:rounded-xl overflow-hidden shadow-sm">
                                    @if($history->novel->cover_image_url)
                                        <img src="{{ $history->novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($history->novel->cover_image)
                                        <img src="{{ asset('storage/' . $history->novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @else
                                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2 text-center">
                                            <span class="text-[10px] text-slate-400 font-bold">{{ $history->novel->title }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-grow min-w-0">
                                    <div class="flex items-center gap-2 mb-1 md:mb-2">
                                        <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[8px] md:text-[10px] font-bold rounded uppercase tracking-wider">
                                            {{ $history->novel->genres->first()->name ?? 'Novel' }}
                                        </span>
                                        <span class="text-[8px] md:text-[10px] text-slate-400 font-medium">{{ $history->updated_at->diffForHumans() }}</span>
                                    </div>
                                    <h4 class="text-sm md:text-lg font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors mb-0.5 md:mb-1 truncate">{{ $history->novel->title }}</h4>
                                    <div class="flex items-center gap-1.5 md:gap-2 text-slate-500 dark:text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 md:h-4 md:w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                        <p class="text-[10px] md:text-xs font-medium truncate">{{ $history->chapter->title }}</p>
                                    </div>
                                </div>

                                <a href="{{ route('chapters.show', [$history->novel->slug, $history->chapter->slug]) }}" class="flex-shrink-0 px-4 md:px-6 py-2 md:py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] md:text-xs font-bold rounded-xl hover:bg-indigo-600 dark:hover:bg-indigo-50 transition-all text-center">
                                    Continue
                                </a>
                            </div>
                        @empty
                            <div class="py-12 md:py-20 text-center bg-slate-50 dark:bg-slate-800/30 rounded-2xl md:rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-white dark:bg-slate-800 rounded-xl md:rounded-2xl flex items-center justify-center mx-auto text-slate-300 dark:text-slate-600 mb-3 md:mb-4 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h4 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-1">No History Yet</h4>
                                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-5 md:mb-6">It looks like you haven't started reading any novels yet.</p>
                                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 md:px-6 py-2.5 md:py-3 bg-indigo-600 text-white text-xs md:text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                                    Explore Novels
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recommendations Tab -->
                <div x-show="activeTab === 'recommendations'" class="space-y-5 md:space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">Picked For You</h3>
                            <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400">Based on novels you like.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
                        @foreach($recommendations as $novel)
                            <a href="{{ route('novels.show', $novel->slug) }}" class="group bg-white dark:bg-slate-900 p-3 md:p-4 rounded-xl md:rounded-2xl border border-slate-200 dark:border-slate-800 flex gap-4 transition-all duration-300 hover:border-indigo-500/30 hover:shadow-sm">
                                <div class="w-16 h-24 md:w-20 md:h-28 flex-shrink-0 rounded-lg md:rounded-xl overflow-hidden shadow-sm">
                                    @if($novel->cover_image_url)
                                        <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($novel->cover_image)
                                        <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @else
                                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2 text-center">
                                            <span class="text-[10px] text-slate-400 font-bold">{{ $novel->title }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-grow flex flex-col justify-between py-0.5 md:py-1 min-w-0">
                                    <div>
                                        <div class="flex items-center gap-1.5 md:gap-2 mb-1 md:mb-1.5">
                                            <div class="flex text-amber-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 md:h-3.5 md:w-3.5 fill-current" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            </div>
                                            <span class="text-[9px] md:text-[10px] font-bold text-slate-600 dark:text-slate-400">{{ number_format($novel->rating_avg, 1) }}</span>
                                        </div>
                                        <h4 class="text-xs md:text-sm font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-indigo-600 transition-colors mb-1.5 md:mb-2 leading-snug">{{ $novel->title }}</h4>
                                    </div>
                                    <div class="flex flex-wrap gap-1 md:gap-1.5">
                                        @foreach($novel->genres->take(2) as $genre)
                                            <span class="px-1.5 md:px-2 py-0.5 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[8px] md:text-[10px] font-bold rounded uppercase tracking-wider border border-slate-100 dark:border-slate-700/50">{{ $genre->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Become Writer Tab -->
                @if($user->role === 'user')
                <div x-show="activeTab === 'become_writer'" class="space-y-6 md:space-y-8">
                    <div class="text-center max-w-2xl mx-auto py-6 md:py-10">
                        <div class="w-16 h-16 md:w-24 md:h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-[2rem] flex items-center justify-center mx-auto text-emerald-600 dark:text-emerald-400 mb-6 md:mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <h2 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white mb-3 md:mb-4">Start Your Writing Journey</h2>
                        <p class="text-sm md:text-lg text-slate-500 dark:text-slate-400 leading-relaxed">Share your imagination with thousands of readers on Quoros.</p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4 md:gap-6">
                        @foreach([
                            ['title' => 'Creative Freedom', 'desc' => 'Write any genre you like without limitations.', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
                            ['title' => 'Build a Community', 'desc' => 'Get loyal fans and direct interaction with readers.', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['title' => 'Track Statistics', 'desc' => "Analyze your work's performance with a complete writer dashboard.", 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 012 2h2a2 2 0 012-2'],
                        ] as $f)
                        <div class="p-6 md:p-8 bg-white dark:bg-slate-900 rounded-2xl md:rounded-3xl border border-slate-100 dark:border-slate-800 transition-all hover:border-emerald-500/30">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-5 md:mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}" /></svg>
                            </div>
                            <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-2">{{ $f['title'] }}</h3>
                            <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ $f['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-8 md:mt-12 p-6 md:p-10 bg-emerald-600 rounded-[2rem] md:rounded-[3rem] text-white text-center relative overflow-hidden shadow-xl shadow-emerald-600/20">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.2),transparent_50%)]"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl md:text-2xl font-bold mb-2 md:mb-3">Ready to start?</h3>
                            <p class="text-emerald-50 text-xs md:text-sm mb-6 md:mb-8 max-w-lg mx-auto opacity-90">By clicking the button below, your account will immediately have access to Writer features.</p>
                            <form action="{{ route('become-writer') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-8 md:px-12 py-3 md:py-4 bg-white text-emerald-600 font-black rounded-xl md:rounded-2xl hover:bg-emerald-50 transition-all shadow-lg text-xs md:text-sm uppercase tracking-widest">
                                    Register as Writer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection