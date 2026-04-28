@extends('layouts.app')

@section('content')
<!-- Banner Carousel -->
<div class="relative mb-10 md:mb-16 rounded-3xl md:rounded-[3rem] overflow-hidden shadow-2xl shadow-indigo-200/50 dark:shadow-none group" 
     x-data="{ 
        activeSlide: 0, 
        slides: {{ $featuredNovels->count() }},
        paused: false,
        timer: null,
        next() { this.activeSlide = (this.activeSlide + 1) % this.slides },
        prev() { this.activeSlide = (this.activeSlide - 1 + this.slides) % this.slides },
        init() {
            if (this.slides > 1) {
                this.startTimer();
            }
        },
        startTimer() {
            this.timer = setInterval(() => {
                if (!this.paused) this.next();
            }, 6000);
        },
        resetTimer() {
            clearInterval(this.timer);
            this.startTimer();
        }
     }"
     @mouseenter="paused = true"
     @mouseleave="paused = false">
    
    <div class="relative h-[500px] sm:h-[550px] md:h-[600px] bg-slate-950">
        @foreach($featuredNovels as $index => $novel)
            <div x-show="activeSlide === {{ $index }}" 
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 transform scale-105"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="absolute inset-0 w-full h-full">
                
                <!-- Background Layer -->
                <div class="absolute inset-0 overflow-hidden">
                    @if($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover blur-sm opacity-40 scale-110 object-center">
                    @endif
                    <!-- Dynamic Gradients -->
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/80 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80"></div>
                </div>

                <!-- Content Grid -->
                <div class="relative h-full max-w-7xl mx-auto px-6 md:px-16 flex items-center">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center w-full">
                        <!-- Text Content -->
                        <div class="space-y-4 md:space-y-8 text-left h-full flex flex-col justify-center"
                             x-show="activeSlide === {{ $index }}"
                             x-transition:enter="transition ease-out duration-700 delay-300"
                             x-transition:enter-start="opacity-0 translate-y-8"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            
                            <!-- Fixed Height Badges -->
                            <div class="flex flex-wrap gap-2 items-center">
                                <span class="px-3 md:px-4 py-1 md:py-1.5 bg-amber-500 text-slate-950 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] rounded-full shadow-lg shadow-amber-500/20">
                                    Featured
                                </span>
                                @foreach($novel->genres->take(2) as $genre)
                                    <span class="px-3 md:px-4 py-1 md:py-1.5 bg-white/10 backdrop-blur-md text-white text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] rounded-full border border-white/10">
                                        {{ $genre->name }}
                                    </span>
                                @endforeach
                            </div>

                            <!-- Fixed Height Title & Author -->
                            <div class="space-y-3 md:space-y-4">
                                <div class="min-h-[5rem] md:h-36 flex flex-col justify-end">
                                    <h2 class="text-3xl md:text-6xl font-black text-white leading-[1.1] tracking-tight drop-shadow-2xl line-clamp-2">
                                        {{ $novel->title }}
                                    </h2>
                                </div>
                                
                                <div class="flex items-center gap-3 text-slate-300 font-bold text-xs md:text-sm">
                                    <div class="flex items-center gap-2 px-3 py-1 bg-white/5 rounded-lg border border-white/10">
                                        <div class="w-5 h-5 rounded-full bg-indigo-500 flex items-center justify-center text-[10px] text-white">
                                            {{ substr($novel->author->name, 0, 1) }}
                                        </div>
                                        <span class="text-white truncate max-w-[100px] md:max-w-none">{{ $novel->author->name }}</span>
                                    </div>
                                    <span class="text-slate-600">|</span>
                                    <span class="text-indigo-400 uppercase text-[9px] md:text-[10px] tracking-widest font-black">Baru Diperbarui</span>
                                </div>
                            </div>

                            <!-- Fixed Height Description -->
                            <div class="min-h-[4rem] md:h-24">
                                <p class="text-slate-400 text-sm md:text-lg leading-relaxed line-clamp-3 max-w-xl font-medium">
                                    {{ $novel->description ?? 'Tidak ada deskripsi tersedia untuk novel ini.' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-3 md:gap-4 pt-2 md:pt-4">
                                <!-- Primary Action -->
                                <a href="{{ route('novels.show', $novel->slug) }}" class="group/btn relative inline-flex items-center gap-3 md:gap-4 px-6 md:px-10 py-3 md:py-4 bg-indigo-600 text-white text-sm md:text-base font-black rounded-2xl overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-indigo-600/30">
                                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-1000"></div>
                                    <span class="relative">Baca Sekarang</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 relative group-hover/btn:translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                
                                <!-- Secondary Actions & Navigation Group -->
                                <div class="flex items-center p-1 bg-white/5 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-xl">
                                    <!-- Bookmark -->
                                    <button class="p-2.5 md:p-3.5 text-white/70 hover:text-white hover:bg-white/10 rounded-xl transition-all group/bookmark">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 group-hover/bookmark:fill-white transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                                    </button>

                                    <!-- Vertical Divider -->
                                    <div class="w-[1px] h-5 md:h-6 bg-white/10 mx-1"></div>

                                    <!-- Prev -->
                                    <button @click="prev(); resetTimer()" class="p-2.5 md:p-3.5 text-white/50 hover:text-white hover:bg-white/10 rounded-xl transition-all group/nav">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 group-hover/nav:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                                    </button>

                                    <!-- Line Indicators -->
                                    <div class="flex items-center gap-1 md:gap-1.5 px-1 md:px-2">
                                        @foreach($featuredNovels as $dotIndex => $dotNovel)
                                            <button @click="activeSlide = {{ $dotIndex }}; resetTimer()" 
                                                    class="h-1 rounded-full transition-all duration-500 overflow-hidden"
                                                    :class="activeSlide === {{ $dotIndex }} ? 'w-4 md:w-6 bg-indigo-500' : 'w-1 md:w-1.5 bg-white/20 hover:bg-white/40'">
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Next -->
                                    <button @click="next(); resetTimer()" class="p-2.5 md:p-3.5 text-white/50 hover:text-white hover:bg-white/10 rounded-xl transition-all group/nav">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 group-hover/nav:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Cover Image -->
                        <div class="hidden lg:block relative"
                             x-show="activeSlide === {{ $index }}"
                             x-transition:enter="transition ease-out duration-1000 delay-500"
                             x-transition:enter-start="opacity-0 translate-x-12 rotate-3"
                             x-transition:enter-end="opacity-100 translate-x-0 rotate-0">
                            <div class="relative z-10 w-72 h-[420px] mx-auto rounded-3xl overflow-hidden shadow-[0_32px_64px_-16px_rgba(0,0,0,0.6)] border-4 border-white/10 group-hover:scale-105 transition-transform duration-700">
                                @if($novel->cover_image)
                                    <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                        <span class="text-white font-bold">{{ $novel->title }}</span>
                                    </div>
                                @endif
                            </div>
                            <!-- Decorative Elements -->
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-indigo-600/20 blur-[100px] rounded-full -z-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

<style>
    @keyframes shimmer {
        100% { transform: translateX(100%); }
    }
</style>

<!-- Filters -->
<div class="mb-8 -mx-6 px-6 overflow-x-auto no-scrollbar">
    <div class="flex items-center gap-2 min-w-max pb-2">
        <a href="{{ route('home') }}" 
           class="px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap {{ !request('genre') ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:border-indigo-600 dark:hover:border-indigo-400' }} transition-all shadow-sm">
           Semua
        </a>
        @foreach($genres as $genre)
            <a href="{{ route('home', ['genre' => $genre->slug]) }}" 
               class="px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap {{ request('genre') == $genre->slug ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:border-indigo-600 dark:hover:border-indigo-400' }} transition-all shadow-sm">
               {{ $genre->name }}
            </a>
        @endforeach
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<!-- Section: Baru Diupdate -->
<div class="mb-16">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
            <h2 class="text-2xl font-bold">Baru Diupdate</h2>
        </div>
        <a href="{{ route('novels.updated') }}" class="text-emerald-600 dark:text-emerald-400 text-sm font-bold hover:underline">Lihat Semua</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($recentlyUpdated as $novel)
            <a href="{{ route('novels.show', $novel->slug) }}" class="flex gap-4 p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-emerald-500 transition-all group">
                <div class="w-20 h-28 flex-shrink-0 rounded-xl overflow-hidden shadow-sm">
                    @if($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2">
                            <span class="text-[10px] text-slate-400 font-bold text-center">{{ $novel->title }}</span>
                        </div>
                    @endif
                </div>
                <div class="flex flex-col justify-center flex-grow">
                    <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-emerald-600 transition-colors line-clamp-1 mb-1">{{ $novel->title }}</h3>
                    <p class="text-xs text-slate-500 mb-2">Oleh {{ $novel->author->name }}</p>
                    <div class="flex items-center justify-between mt-auto">
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-full border border-emerald-100 dark:border-emerald-800">
                            Chapter {{ $novel->chapters->count() }}
                        </span>
                        <span class="text-[10px] text-slate-400 italic">
                            {{ $novel->chapters->max('created_at')->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>

<!-- Section: Leaderboard & Novel Terbaru -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-12 mb-16">
    <!-- Left Column: Novel Terbaru (3/4 width on desktop) -->
    <div class="lg:col-span-3">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-indigo-600 rounded-full"></div>
                <h2 class="text-2xl font-bold">Novel Terbaru</h2>
            </div>
            <a href="#" class="text-indigo-600 dark:text-indigo-400 text-sm font-bold hover:underline">Lihat Semua</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @forelse($novels as $novel)
                <a href="{{ route('novels.show', $novel->slug) }}" class="group">
                    <div class="relative aspect-[3/4] mb-3 rounded-2xl overflow-hidden shadow-md group-hover:shadow-xl group-hover:shadow-indigo-500/20 transition-shadow">
                        @if($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-4">
                                <span class="text-xs text-slate-400 font-bold text-center">{{ $novel->title }}</span>
                            </div>
                        @endif
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <span class="text-white text-[10px] font-bold px-2 py-1 bg-indigo-600 rounded-lg">Baca Sekarang</span>
                        </div>

                        <!-- Rating Badge -->
                        @if($novel->rating_avg > 0)
                        <div class="absolute top-2 right-2 px-2 py-1 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm rounded-lg flex items-center gap-1 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-200">{{ number_format($novel->rating_avg, 1) }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 transition-colors line-clamp-1 text-sm mb-1">{{ $novel->title }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-slate-400">{{ $novel->author->name }}</span>
                        <span class="w-1 h-1 bg-slate-300 dark:bg-slate-700 rounded-full"></span>
                        <span class="text-[10px] text-indigo-500 font-semibold">{{ $novel->genres->first()->name ?? 'General' }}</span>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <p class="text-slate-500 font-medium">Belum ada novel yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $novels->links() }}
        </div>
    </div>

    <!-- Right Column: Leaderboard (1/4 width on desktop) -->
    <div class="lg:col-span-1" x-data="{ tab: 'weekly' }">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1.5 h-8 bg-amber-500 rounded-full"></div>
            <h2 class="text-2xl font-bold">Top Novels</h2>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-2 mb-6 flex">
            <button @click="tab = 'weekly'" 
                    :class="tab === 'weekly' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800'"
                    class="flex-1 py-2 rounded-2xl text-xs font-bold transition-all">
                Weekly
            </button>
            <button @click="tab = 'monthly'" 
                    :class="tab === 'monthly' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800'"
                    class="flex-1 py-2 rounded-2xl text-xs font-bold transition-all">
                Monthly
            </button>
        </div>

        <!-- Weekly Top -->
        <div x-show="tab === 'weekly'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            @forelse($weeklyTop as $index => $novel)
                <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group mb-2 last:mb-0">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center font-black text-xl {{ $index === 0 ? 'text-amber-500' : ($index === 1 ? 'text-slate-400' : ($index === 2 ? 'text-amber-700' : 'text-slate-300')) }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="w-12 h-16 flex-shrink-0 rounded-lg overflow-hidden">
                        @if($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                <span class="text-[8px] text-slate-400">No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <h4 class="font-bold text-sm text-slate-800 dark:text-slate-100 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $novel->title }}</h4>
                        <p class="text-[10px] text-slate-500 line-clamp-1">{{ $novel->author->name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] font-bold text-indigo-600">{{ number_format($novel->view_count) }} views</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-center py-8 text-slate-400 text-sm">Belum ada data mingguan.</p>
            @endforelse
        </div>

        <!-- Monthly Top -->
        <div x-show="tab === 'monthly'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            @forelse($monthlyTop as $index => $novel)
                <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group mb-2 last:mb-0">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center font-black text-xl {{ $index === 0 ? 'text-amber-500' : ($index === 1 ? 'text-slate-400' : ($index === 2 ? 'text-amber-700' : 'text-slate-300')) }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="w-12 h-16 flex-shrink-0 rounded-lg overflow-hidden">
                        @if($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                <span class="text-[8px] text-slate-400">No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <h4 class="font-bold text-sm text-slate-800 dark:text-slate-100 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $novel->title }}</h4>
                        <p class="text-[10px] text-slate-500 line-clamp-1">{{ $novel->author->name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] font-bold text-indigo-600">{{ number_format($novel->view_count) }} views</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-center py-8 text-slate-400 text-sm">Belum ada data bulanan.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Section: Populer (Placeholder for logic) -->
<div class="mb-12">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-1.5 h-8 bg-rose-500 rounded-full"></div>
        <h2 class="text-2xl font-bold">Populer Minggu Ini</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($novels->take(6) as $novel)
        <a href="{{ route('novels.show', $novel->slug) }}" class="flex gap-4 p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-indigo-600 dark:hover:border-indigo-400 transition-all group">
            <div class="w-20 h-28 flex-shrink-0 rounded-xl overflow-hidden shadow-sm">
                @if($novel->cover_image)
                    <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2">
                        <span class="text-[10px] text-slate-400 font-bold text-center">{{ $novel->title }}</span>
                    </div>
                @endif
            </div>
            <div class="flex flex-col justify-center">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1 mb-1">{{ $novel->title }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">{{ $novel->author->name }}</p>
                <div class="flex items-center gap-2">
                    <span class="text-amber-500 flex items-center gap-1 text-xs font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        {{ number_format($novel->rating_avg, 1) }}
                    </span>
                    <span class="text-slate-400 dark:text-slate-600 text-xs">•</span>
                    <span class="text-slate-400 dark:text-slate-500 text-xs font-medium flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        {{ number_format($novel->view_count) }}
                    </span>
                    <span class="text-slate-400 dark:text-slate-600 text-xs">•</span>
                    <span class="text-slate-400 dark:text-slate-500 text-xs font-medium">{{ $novel->chapters->count() }} Chapter</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
