@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{
    activeTab: '{{ request()->get('tab', ($user->role === 'user' ? 'bookmarks' : 'settings')) }}',
    profilePhotoPreview: null,
    updateProfilePhotoPreview(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            initCropper(input, 'profile-photo-img', { 
                aspectRatio: 1, 
                width: 400, 
                height: 400 
            });
            // preview will be updated by saveCrop global function
        }
    }
}">
    <!-- Clean Header Section -->
    <div class="relative mb-6 md:mb-8 p-5 md:p-8 rounded-2xl md:rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-slate-50 dark:bg-slate-900/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6 md:gap-8">
            <div class="flex items-center gap-4 md:gap-6">
                <div class="relative">
                    <div class="w-16 h-16 md:w-24 md:h-24 rounded-2xl overflow-hidden ring-4 ring-slate-50 dark:ring-slate-800 shadow-md">
                        @if($user->profile_photo_url)
                            <img src="{{ $user->profile_photo_url }}" class="w-full h-full object-cover" loading="lazy">
                        @elseif($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover" loading="lazy">
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
                        Hai, {{ $user->name }}
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-[11px] md:text-sm font-medium truncate">
                        {{ $user->role === 'admin' ? 'Administrator' : ($user->role === 'writer' ? 'Penulis' : 'Pembaca') }} • Sejak {{ $user->created_at->format('M Y') }}
                    </p>
                </div>
            </div>

            <div class="flex gap-3 md:gap-4">
                <div class="flex-1 md:flex-none px-4 md:px-6 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl md:rounded-2xl border border-slate-100 dark:border-slate-700/50 text-center md:text-left">
                    <p class="text-[9px] md:text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5 md:mb-1">Jam Baca</p>
                    <p class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">{{ $totalReadingHours }}<span class="text-xs ml-0.5 text-slate-400 font-medium">h</span></p>
                </div>
                <div class="flex-1 md:flex-none px-4 md:px-6 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl md:rounded-2xl border border-slate-100 dark:border-slate-700/50 text-center md:text-left">
                    <p class="text-[9px] md:text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5 md:mb-1">Koin</p>
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
                        <span>Profil Saya</span>
                    </a>

                    <div class="h-px bg-slate-100 dark:bg-slate-800 my-1 hidden lg:block"></div>

                    @if($user->role === 'user')
                    <button @click="activeTab = 'bookmarks'" 
                        :class="activeTab === 'bookmarks' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white'" 
                        class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                        <span>Bookmark</span>
                    </button>

                    <button @click="activeTab = 'history'" 
                        :class="activeTab === 'history' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white'" 
                        class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Riwayat</span>
                    </button>

                    <button @click="activeTab = 'recommendations'" 
                        :class="activeTab === 'recommendations' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white'" 
                        class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        <span>Rekomendasi</span>
                    </button>

                    @endif

                    <button @click="activeTab = 'settings'" 
                        :class="activeTab === 'settings' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white'" 
                        class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Pengaturan</span>
                    </button>

                    @if($user->role === 'user')
                    <button @click="activeTab = 'become_writer'" 
                        :class="activeTab === 'become_writer' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white'" 
                        class="flex items-center gap-2 md:gap-3 px-4 py-2.5 md:py-3 rounded-xl text-xs md:text-sm font-semibold transition-all duration-200 group whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        <span>Penulis</span>
                    </button>
                    @endif
                </nav>

                @if($user->role === 'admin' || $user->role === 'writer')
                <div class="mt-4 md:mt-6 pt-4 md:pt-6 border-t border-slate-100 dark:border-slate-800 hidden lg:block">
                    <p class="px-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">Kontributor</p>
                    <div class="space-y-1">
                        @if($user->role === 'admin')
                            <a href="{{ route('admin.carousel.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                                <span>Carousel</span>
                            </a>
                        @endif
                        <a href="{{ route('writer.stats') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 012 2h2a2 2 0 012-2" /></svg>
                            <span>Dashboard Penulis</span>
                        </a>
                        <a href="{{ route('writer.novels.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            <span>Karya Saya</span>
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
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Target Harian</h3>
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
                    <p class="text-xs text-slate-500 dark:text-slate-400">15 menit lagi untuk mencapai target!</p>
                </div>

                <div class="space-y-4">
                    <div class="relative h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="absolute top-0 left-0 h-full bg-emerald-600 rounded-full transition-all duration-500" style="width: 75%"></div>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-3">
                            <div class="text-xl">🔥</div>
                            <div>
                                <p class="text-xs font-bold text-slate-900 dark:text-white">5 Hari Beruntun!</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">Jangan biarkan apinya padam.</p>
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
                                Lihat Detail
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

            @if($user->role === 'user')
            <!-- Hero: Continue Reading -->
            @if($lastRead)
            <section class="bg-slate-900 dark:bg-indigo-950 rounded-2xl md:rounded-3xl overflow-hidden shadow-sm">
                <div class="flex flex-col md:flex-row items-center gap-5 md:gap-8 p-5 md:p-10">
                    <div class="w-24 md:w-40 flex-shrink-0 rounded-xl md:rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('storage/' . $lastRead->novel->cover_image) }}" class="w-full h-full object-cover aspect-[3/4]">
                    </div>
                    
                    <div class="flex-grow space-y-4 md:space-y-6 text-center md:text-left w-full">
                        <div>
                            <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-indigo-500/20 rounded-full text-indigo-300 text-[9px] md:text-[10px] font-bold uppercase tracking-wider mb-2 md:mb-4">
                                <span class="flex h-1.5 w-1.5 md:h-2 md:w-2 rounded-full bg-indigo-500"></span>
                                Sedang Dibaca
                            </div>
                            <h2 class="text-lg md:text-4xl font-bold text-white mb-1 md:mb-2 line-clamp-1 md:line-clamp-2">{{ $lastRead->novel->title }}</h2>
                            <p class="text-slate-400 text-[10px] md:text-sm font-medium">
                                Terakhir: <span class="text-white font-bold">{{ $lastRead->chapter->title }}</span>
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
                            Lanjutkan Membaca
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 md:h-5 md:w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>
            </section>
            @endif
            @endif

            <!-- Writer Insights (Only for Writers/Admins) -->
            @if($writerStats)
            <section class="bg-white dark:bg-slate-900 p-5 md:p-10 rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6 mb-6 md:mb-10">
                    <div class="text-center md:text-left">
                        <h3 class="text-lg md:text-2xl font-bold text-slate-900 dark:text-white">Writer Insights</h3>
                        <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400">Statistik performa karya Anda bulan ini.</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('writer.stats') }}" class="inline-flex justify-center items-center px-5 md:px-6 py-2.5 md:py-3 bg-indigo-600 text-white rounded-xl text-[10px] md:text-xs font-bold hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200 dark:shadow-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 012 2h2a2 2 0 012-2" /></svg>
                            Lihat Statistik Detail
                        </a>
                        <a href="{{ route('writer.novels.index') }}" class="inline-flex justify-center items-center px-5 md:px-6 py-2.5 md:py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-[10px] md:text-xs font-bold hover:bg-indigo-600 dark:hover:bg-indigo-50 transition-colors">
                            Kelola Semua Karya
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                    <!-- Views Stat -->
                    <div class="p-3.5 md:p-6 rounded-xl md:rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 flex flex-col items-center md:items-start text-center md:text-left">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-2.5 md:mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </div>
                        <p class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5 md:mb-1">Total Views</p>
                        <h4 class="text-base md:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($writerStats['total_views']) }}</h4>
                    </div>

                    <!-- Reviews Stat -->
                    <div class="p-3.5 md:p-6 rounded-xl md:rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 flex flex-col items-center md:items-start text-center md:text-left">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-violet-500/10 flex items-center justify-center text-violet-600 dark:text-violet-400 mb-2.5 md:mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                        </div>
                        <p class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5 md:mb-1">Total Ulasan</p>
                        <h4 class="text-base md:text-2xl font-bold text-slate-900 dark:text-white">{{ $writerStats['total_comments'] }}</h4>
                    </div>

                    <!-- Rating Stat -->
                    <div class="p-3.5 md:p-6 rounded-xl md:rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 flex flex-col items-center md:items-start text-center md:text-left">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400 mb-2.5 md:mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                        </div>
                        <p class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5 md:mb-1">Avg Rating</p>
                        <h4 class="text-base md:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($writerStats['avg_rating'], 1) }}</h4>
                    </div>

                    <!-- Works Stat -->
                    <div class="p-3.5 md:p-6 rounded-xl md:rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 flex flex-col items-center md:items-start text-center md:text-left">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-2.5 md:mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <p class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5 md:mb-1">Total Karya</p>
                        <h4 class="text-base md:text-2xl font-bold text-slate-900 dark:text-white">{{ $writerStats['novel_count'] }}</h4>
                    </div>
                </div>
            </section>

            @endif

            <!-- Tab Contents -->
            <div class="min-h-[400px] md:min-h-[500px]">
                @if($user->role === 'user')
                <!-- Bookmark Tab -->
                <div x-show="activeTab === 'bookmarks'" class="space-y-5 md:space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">Bookmark Saya</h3>
                            <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400">Kumpulan novel yang Anda ikuti.</p>
                        </div>
                        <span class="px-2.5 md:px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full text-[9px] md:text-xs font-bold text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                            {{ count($bookmarks) }} Novel
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                        @forelse($bookmarks as $bookmark)
                            <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="group block">
                                <div class="relative aspect-[3/4] rounded-xl md:rounded-2xl overflow-hidden shadow-sm transition-shadow group-hover:shadow-md">
                                    @if($bookmark->novel->cover_image_url)
                                        <img src="{{ $bookmark->novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy">
                                    @elseif($bookmark->novel->cover_image)
                                        <img src="{{ asset('storage/' . $bookmark->novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy">
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
                                <h4 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-1">Belum Ada Bookmark</h4>
                                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-5 md:mb-6">Mulai jelajahi novel menarik dan simpan di sini!</p>
                                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 md:px-6 py-2.5 md:py-3 bg-indigo-600 text-white text-xs md:text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                                    Cari Novel
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
                            <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">Riwayat Baca</h3>
                            <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400">Lanjutkan petualangan yang sempat tertunda.</p>
                        </div>
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-wider">Terakhir Dibaca</span>
                    </div>

                    <div class="space-y-3 md:space-y-4">
                        @forelse($histories as $history)
                            <div class="group bg-white dark:bg-slate-900 p-3 md:p-4 rounded-xl md:rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-row items-center gap-4 md:gap-6 transition-all duration-300 hover:border-indigo-500/30 hover:shadow-sm">
                                <div class="w-16 h-24 md:w-24 md:h-32 flex-shrink-0 rounded-lg md:rounded-xl overflow-hidden shadow-sm">
                                    @if($history->novel->cover_image_url)
                                        <img src="{{ $history->novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy">
                                    @elseif($history->novel->cover_image)
                                        <img src="{{ asset('storage/' . $history->novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy">
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
                                    Lanjutkan
                                </a>
                            </div>
                        @empty
                            <div class="py-12 md:py-20 text-center bg-slate-50 dark:bg-slate-800/30 rounded-2xl md:rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-white dark:bg-slate-800 rounded-xl md:rounded-2xl flex items-center justify-center mx-auto text-slate-300 dark:text-slate-600 mb-3 md:mb-4 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h4 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-1">Belum Ada Riwayat</h4>
                                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-5 md:mb-6">Sepertinya Anda belum mulai membaca novel apa pun.</p>
                                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 md:px-6 py-2.5 md:py-3 bg-indigo-600 text-white text-xs md:text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                                    Jelajahi Novel
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recommendations Tab -->
                <div x-show="activeTab === 'recommendations'" class="space-y-5 md:space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">Pilihan Untuk Anda</h3>
                            <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400">Berdasarkan novel yang Anda sukai.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
                        @foreach($recommendations as $novel)
                            <a href="{{ route('novels.show', $novel->slug) }}" class="group bg-white dark:bg-slate-900 p-3 md:p-4 rounded-xl md:rounded-2xl border border-slate-200 dark:border-slate-800 flex gap-4 transition-all duration-300 hover:border-indigo-500/30 hover:shadow-sm">
                                <div class="w-16 h-24 md:w-20 md:h-28 flex-shrink-0 rounded-lg md:rounded-xl overflow-hidden shadow-sm">
                                    @if($novel->cover_image_url)
                                        <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy">
                                    @elseif($novel->cover_image)
                                        <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy">
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

                @endif

                <!-- Become Writer Tab -->
                @if($user->role === 'user')
                <div x-show="activeTab === 'become_writer'" class="space-y-5 md:space-y-6">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="relative h-40 md:h-48 bg-indigo-600 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-violet-600 opacity-90"></div>
                            <div class="absolute top-0 right-0 w-48 md:w-64 h-48 md:h-64 bg-white/10 rounded-full -mr-24 md:-mr-32 -mt-24 md:-mt-32 blur-2xl md:blur-3xl"></div>
                            <div class="absolute bottom-0 left-0 w-32 md:w-48 h-32 md:h-48 bg-white/10 rounded-full -ml-16 md:-ml-24 -mb-16 md:-mb-24 blur-xl md:blur-2xl"></div>
                            
                            <div class="relative h-full flex flex-col items-center justify-center text-center px-6">
                                <h3 class="text-xl md:text-3xl font-bold text-white mb-1.5 md:mb-2">Mulai Perjalanan Menulismu</h3>
                                <p class="text-indigo-100 text-[10px] md:text-base max-w-lg">Bagikan imajinasimu kepada ribuan pembaca di Mural.</p>
                            </div>
                        </div>

                        <div class="p-6 md:p-10">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 mb-8 md:mb-12">
                                <div class="text-center">
                                    <div class="w-12 h-12 md:w-16 md:h-16 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl md:rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mx-auto mb-3 md:mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </div>
                                    <h4 class="text-sm md:text-base font-bold text-slate-900 dark:text-white mb-1 md:mb-2">Kebebasan Berkreasi</h4>
                                    <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Tulis genre apa pun yang kamu suka tanpa batasan.</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-12 h-12 md:w-16 md:h-16 bg-violet-50 dark:bg-violet-900/30 rounded-xl md:rounded-2xl flex items-center justify-center text-violet-600 dark:text-violet-400 mx-auto mb-3 md:mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    </div>
                                    <h4 class="text-sm md:text-base font-bold text-slate-900 dark:text-white mb-1 md:mb-2">Bangun Komunitas</h4>
                                    <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Dapatkan penggemar setia dan interaksi langsung dengan pembaca.</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-12 h-12 md:w-16 md:h-16 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl md:rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mx-auto mb-3 md:mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                    </div>
                                    <h4 class="text-sm md:text-base font-bold text-slate-900 dark:text-white mb-1 md:mb-2">Pantau Statistik</h4>
                                    <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Analisis performa karyamu dengan dashboard penulis yang lengkap.</p>
                                </div>
                            </div>

                            <div class="max-w-xl mx-auto p-5 md:p-8 bg-slate-50 dark:bg-slate-800/50 rounded-2xl md:rounded-3xl border border-slate-100 dark:border-slate-700/50 text-center">
                                <h4 class="text-sm md:text-lg font-bold text-slate-900 dark:text-white mb-1 md:mb-2">Siap untuk memulai?</h4>
                                <p class="text-[10px] md:text-sm text-slate-500 dark:text-slate-400 mb-6 md:mb-8">Dengan menekan tombol di bawah, akun Anda akan langsung memiliki akses ke fitur-fitur Penulis.</p>
                                
                                <form action="{{ route('dashboard.become-writer') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex w-full md:w-auto justify-center items-center gap-2 md:gap-3 px-8 md:px-10 py-3.5 md:py-4 bg-indigo-600 text-white font-bold rounded-xl md:rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 active:scale-[0.98] text-xs md:text-base">
                                        <span>Daftar Sebagai Penulis</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                @endif

                <!-- Settings Tab -->
                <div x-show="activeTab === 'settings'" class="space-y-6">
                    @if(session('success'))
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="rounded-2xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
                            Ada data yang belum valid. Cek kembali field profil lalu simpan lagi.
                        </div>
                    @endif

                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Profil Saya</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola informasi publik dan pengaturan akun Anda.</p>
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
                                <div class="space-y-8">
                                    <!-- Photo Upload -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 md:mb-4">Foto Profil</label>
                                        <div class="flex flex-col sm:flex-row items-center gap-4 md:gap-6">
                                            <div class="relative">
                                                <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 border-4 border-slate-50 dark:border-slate-800 shadow-sm">
                                                    @if($user->profile_photo_url)
                                                        <img id="profile-photo-img" src="{{ $user->profile_photo_url }}" class="w-full h-full object-cover">
                                                    @elseif($user->profile_photo)
                                                        <img id="profile-photo-img" src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <img id="profile-photo-img" src="" class="w-full h-full object-cover hidden">
                                                        <div id="profile-photo-placeholder" class="w-full h-full bg-indigo-600 flex items-center justify-center text-2xl md:text-3xl font-bold text-white uppercase">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-grow text-center sm:text-left w-full sm:w-auto">
                                                <label for="profile_photo" class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] md:text-xs font-bold rounded-lg cursor-pointer hover:bg-indigo-600 dark:hover:bg-indigo-50 transition-colors">
                                                    Ganti Foto
                                                </label>
                                                <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="hidden" @change="updateProfilePhotoPreview($event); if(document.getElementById('profile-photo-placeholder')) document.getElementById('profile-photo-placeholder').classList.add('hidden'); document.getElementById('profile-photo-img').classList.remove('hidden')" />
                                                <p class="mt-2 text-[9px] md:text-[10px] text-slate-400">Pilih & Potong Foto (Maks. 2MB)</p>
                                                <p class="mt-1 text-[9px] md:text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">Simpan perubahan untuk menerapkan foto baru.</p>
                                                @error('profile_photo')
                                                    <p class="mt-2 text-[10px] md:text-xs text-red-500 font-medium">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-5 md:space-y-6">
                                        <div>
                                            <label class="block text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5 md:mb-2">Nama Lengkap</label>
                                            <input type="text" name="name" value="{{ $user->name }}" class="w-full px-4 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-xs md:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" placeholder="Nama lengkap...">
                                            @error('name')
                                                <p class="mt-1.5 md:mt-2 text-[10px] md:text-xs text-red-500 font-medium">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5 md:mb-2">Username</label>
                                            <div class="relative">
                                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs md:text-sm">@</span>
                                                <input type="text" name="username" value="{{ $user->username }}" class="w-full pl-9 md:pl-10 pr-4 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-xs md:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" placeholder="username">
                                            </div>
                                            @error('username')
                                                <p class="mt-1.5 md:mt-2 text-[10px] md:text-xs text-red-500 font-medium">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-6 md:space-y-8">
                                    <div>
                                        <label class="block text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5 md:mb-2">Bio Singkat</label>
                                        <textarea name="bio" rows="4" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-xs md:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all resize-none" placeholder="Ceritakan sedikit tentang dirimu...">{{ $user->bio }}</textarea>
                                    </div>

                                    <div class="p-4 md:p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700">
                                        <div class="flex items-center justify-between mb-1.5 md:mb-2">
                                            <h4 class="text-[10px] md:text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Reading List Publik</h4>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_public_reading_list" value="1" {{ $user->is_public_reading_list ? 'checked' : '' }} class="sr-only peer">
                                                <div class="w-10 md:w-11 h-5 md:h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 md:after:h-5 after:w-4 md:after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </label>
                                        </div>
                                        <p class="text-[10px] md:text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">Izinkan orang lain melihat daftar novel yang Anda bookmark.</p>
                                    </div>

                                    <div class="pt-2">
                                        <button type="submit" class="w-full py-3.5 md:py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm active:scale-[0.98] text-xs md:text-base">
                                            Simpan Perubahan
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection