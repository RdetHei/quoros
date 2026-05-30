@extends('layouts.app')

@section('content')
<!-- Banner Carousel -->
<div class="relative mb-10 md:mb-16 rounded-3xl md:rounded-[3rem] overflow-hidden shadow-2xl shadow-slate-900/10 dark:shadow-none group" 
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
    
    <div class="relative h-[450px] sm:h-[500px] md:h-[550px] bg-slate-950">
        @foreach($featuredNovels as $index => $novel)
            <div x-show="activeSlide === {{ $index }}" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 w-full h-full">
                
                <!-- Background Layer -->
                <div class="absolute inset-0 overflow-hidden">
                    @if($novel->cover_image_url)
                        <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover blur-md opacity-30 scale-110 object-center" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                    @elseif($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover blur-md opacity-30 scale-110 object-center" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                    @endif
                    <!-- Dynamic Gradients -->
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-90"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,_rgba(16,185,129,0.08)_0%,_transparent_50%)]"></div>
                </div>

                <!-- Content Grid -->
                <div class="relative h-full max-w-7xl mx-auto px-6 md:px-16 flex items-center">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center w-full">
                        <!-- Text Content -->
                        <div class="lg:col-span-7 text-left"
                             x-show="activeSlide === {{ $index }}"
                             x-transition:enter="transition ease-out duration-700 delay-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                            
                            <div class="flex flex-wrap gap-2 items-center mb-6">
                                <span class="px-3 py-1 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-[0.2em] rounded-full shadow-lg shadow-emerald-900/40">
                                    Unggulan
                                </span>
                                @foreach($novel->genres->take(2) as $genre)
                                    <span class="px-3 py-1 bg-white/5 backdrop-blur-xl text-white/80 text-[9px] font-black uppercase tracking-[0.15em] rounded-full border border-white/10">
                                        {{ $genre->name }}
                                    </span>
                                @endforeach
                            </div>

                            <!-- Fixed Height Title & Author Container -->
                            <div class="h-32 md:h-44 flex flex-col justify-end mb-6">
                                <div x-transition:enter="transition ease-out duration-700 delay-400"
                                     x-transition:enter-start="opacity-0 translate-y-4"
                                     x-transition:enter-end="opacity-100 translate-y-0">
                                    <h2 class="text-3xl md:text-5xl font-black text-white leading-tight tracking-tight drop-shadow-2xl line-clamp-2 mb-4">
                                        {{ $novel->title }}
                                    </h2>
                                    
                                    <div class="flex items-center gap-3 text-slate-300 font-bold text-xs md:text-sm">
                                        <div class="flex items-center gap-2.5 px-3 py-1.5 bg-white/5 backdrop-blur-md rounded-xl border border-white/10">
                                            <div class="w-5 h-5 rounded-full bg-emerald-600 flex items-center justify-center text-[9px] font-black text-white">
                                                {{ substr($novel->author->name, 0, 1) }}
                                            </div>
                                            <span class="text-white font-bold tracking-tight">{{ $novel->author->name }}</span>
                                        </div>
                                        <div class="w-1 h-1 rounded-full bg-emerald-500/50"></div>
                                        <span class="text-slate-400 uppercase text-[9px] tracking-[0.15em] font-black">Baru Diperbarui</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Fixed Height Description Container -->
                            <div class="h-20 md:h-24 mb-8">
                                <p x-transition:enter="transition ease-out duration-700 delay-500"
                                   x-transition:enter-start="opacity-0 translate-y-4"
                                   x-transition:enter-end="opacity-100 translate-y-0"
                                   class="text-slate-400 text-sm md:text-base leading-relaxed line-clamp-3 max-w-xl font-medium opacity-80">
                                    {{ $novel->description ?? 'Tidak ada deskripsi tersedia untuk novel ini.' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-4">
                                <!-- Primary Action -->
                                <a href="{{ route('novels.show', $novel->slug) }}" 
                                   class="group/btn relative inline-flex items-center gap-3 px-6 md:px-8 py-3 md:py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-black rounded-2xl overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-xl shadow-emerald-900/40">
                                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-1000"></div>
                                    <span class="relative">Baca Sekarang</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 relative group-hover/btn:translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                
                                <!-- Secondary Actions & Navigation Group -->
                                <div class="flex items-center p-1 bg-white/5 backdrop-blur-3xl border border-white/10 rounded-2xl shadow-xl">
                                    <!-- Bookmark -->
                                    <button 
                                        x-data="{ 
                                            isBookmarked: {{ ($novel->is_bookmarked ?? false) ? 'true' : 'false' }},
                                            loading: false,
                                            toggleBookmark() {
                                                @auth
                                                    if (this.loading) return;
                                                    this.loading = true;
                                                    fetch('{{ route('bookmarks.toggle', $novel->id) }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Accept': 'application/json',
                                                            'X-Requested-With': 'XMLHttpRequest'
                                                        }
                                                    })
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        this.isBookmarked = data.status === 'added';
                                                        this.loading = false;
                                                    })
                                                    .catch(() => this.loading = false);
                                                @else
                                                    window.location.href = '{{ route('login') }}';
                                                @endauth
                                            }
                                        }"
                                        @click="toggleBookmark()"
                                        :class="isBookmarked ? 'text-rose-500 bg-rose-500/10' : 'text-white/70 hover:text-white hover:bg-white/10'"
                                        class="p-2.5 md:p-3 rounded-xl transition-all group/bookmark"
                                        title="Simpan ke Bookmark">
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                             class="h-4 w-4 md:h-5 md:w-5 transition-all" 
                                             :class="isBookmarked ? 'fill-current' : 'group-hover/bookmark:fill-white'" 
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2-0 012-2h10a2 2-0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>

                                    <div class="w-[1px] h-5 bg-white/10 mx-1.5"></div>

                                    <!-- Navigation -->
                                    <div class="flex items-center gap-0.5">
                                        <button @click="prev(); resetTimer()" class="p-2.5 md:p-3 text-white/50 hover:text-white hover:bg-white/10 rounded-xl transition-all group/nav">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 group-hover/nav:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                                        </button>

                                        <div class="flex items-center gap-1.5 px-1.5">
                                            @foreach($featuredNovels as $dotIndex => $dotNovel)
                                                <button @click="activeSlide = {{ $dotIndex }}; resetTimer()" 
                                                        class="h-1 rounded-full transition-all duration-500"
                                                        :class="activeSlide === {{ $dotIndex }} ? 'w-6 bg-emerald-500' : 'w-1.5 bg-white/20 hover:bg-white/40'">
                                                </button>
                                            @endforeach
                                        </div>

                                        <button @click="next(); resetTimer()" class="p-2.5 md:p-3 text-white/50 hover:text-white hover:bg-white/10 rounded-xl transition-all group/nav">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 group-hover/nav:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Cover Image -->
                        <div class="hidden lg:block lg:col-span-5 relative"
                             x-show="activeSlide === {{ $index }}"
                             x-transition:enter="transition ease-out duration-1000 delay-500"
                             x-transition:enter-start="opacity-0 translate-x-12 scale-105"
                             x-transition:enter-end="opacity-100 translate-x-0 scale-100">
                            <div class="relative z-10 w-64 h-[380px] mx-auto rounded-[2rem] overflow-hidden shadow-[0_32px_64px_-16px_rgba(0,0,0,0.6)] border-4 border-white/10 hover:scale-105 transition-transform duration-700">
                                @if($novel->cover_image_url)
                                    <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                @elseif($novel->cover_image)
                                    <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            </div>
                            <!-- Decorative Elements -->
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-emerald-500/10 blur-[100px] rounded-full -z-10"></div>
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

<!-- Section: Baru Diupdate -->
<div class="mb-16">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-emerald-700 rounded-full"></div>
            <h2 class="text-2xl font-bold">Baru Diupdate</h2>
        </div>
        <a href="{{ route('novels.updated') }}" class="text-emerald-700 dark:text-emerald-500 text-sm font-bold hover:underline">Lihat Semua</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($recentlyUpdated as $novel)
            <div class="flex gap-3 p-2.5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-emerald-700/50 hover:shadow-xl hover:shadow-emerald-700/5 transition-all group h-full">
                <!-- Cover -->
                <a href="{{ route('novels.show', $novel->slug) }}" class="w-20 h-28 flex-shrink-0 rounded-xl overflow-hidden shadow-sm">
                    @if($novel->cover_image_url)
                        <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                    @elseif($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                    @else
                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2">
                            <span class="text-[7px] text-slate-400 font-bold text-center uppercase leading-tight">{{ $novel->title }}</span>
                        </div>
                    @endif
                </a>

                <!-- Info & Chapters -->
                <div class="flex flex-col flex-grow min-w-0 py-0.5">
                    <a href="{{ route('novels.show', $novel->slug) }}" class="mb-1.5">
                        <h3 class="font-black text-slate-800 dark:text-slate-100 group-hover:text-emerald-700 transition-colors line-clamp-1 text-[13px] leading-tight">{{ $novel->title }}</h3>
                    </a>
                    
                    <div class="space-y-1">
                        @foreach($novel->chapters as $chapter)
                            <a href="{{ route('chapters.show', [$novel->slug, $chapter->slug]) }}" class="flex items-center justify-between group/ch py-0.5 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-700/10 transition-colors">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <div class="w-1 h-1 rounded-full bg-emerald-700 {{ $loop->first ? 'animate-pulse' : 'opacity-40' }}"></div>
                                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 group-hover/ch:text-emerald-700 transition-colors truncate">
                                        {{ $chapter->title }}
                                    </span>
                                </div>
                                <span class="text-[7px] font-black text-slate-400 uppercase tracking-tighter shrink-0 ml-1">
                                    {{ $chapter->created_at->diffForHumans(null, true) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Section: Leaderboard & Novel Terbaru -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-12 mb-16">
    <!-- Left Column: Novel Terbaru (3/4 width on desktop) -->
    <div class="lg:col-span-3">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-slate-800 rounded-full"></div>
                <h2 class="text-2xl font-bold">Novel Terbaru</h2>
            </div>
            <a href="#" class="text-slate-800 dark:text-slate-200 text-sm font-bold hover:underline">Lihat Semua</a>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3 md:gap-4">
            @forelse($novels as $novel)
                <a href="{{ route('novels.show', $novel->slug) }}" class="group block">
                    <div class="relative aspect-[2/3] mb-2 rounded-xl overflow-hidden shadow-sm group-hover:shadow-lg group-hover:shadow-slate-500/20 transition-all">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2">
                                <span class="text-[8px] text-slate-400 font-bold text-center uppercase">{{ $novel->title }}</span>
                            </div>
                        @endif
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Rating Badge -->
                        @if($novel->rating_avg > 0)
                        <div class="absolute top-1 right-1 px-1.5 py-0.5 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm rounded-md flex items-center gap-0.5 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            <span class="text-[9px] font-black text-slate-700 dark:text-slate-200">{{ number_format($novel->rating_avg, 1) }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <h3 class="font-black text-slate-800 dark:text-slate-100 group-hover:text-slate-900 dark:group-hover:text-white transition-colors line-clamp-1 text-[11px] mb-0.5">{{ $novel->title }}</h3>
                    <p class="text-[9px] text-slate-500 font-bold truncate">By {{ $novel->author->name }}</p>
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
        
        @if($novels->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $novels->links() }}
            </div>
        @endif
    </div>

    <!-- Right Column: Leaderboard (1/4 width on desktop) -->
    <div class="lg:col-span-1" x-data="{ tab: 'weekly' }">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1.5 h-8 bg-amber-600 rounded-full"></div>
            <h2 class="text-2xl font-bold">Top Novels</h2>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-2 mb-6 flex">
            <button @click="tab = 'weekly'" 
                    :class="tab === 'weekly' ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 shadow-lg' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800'"
                    class="flex-1 py-2 rounded-2xl text-xs font-bold transition-all">
                Weekly
            </button>
            <button @click="tab = 'monthly'" 
                    :class="tab === 'monthly' ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 shadow-lg' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800'"
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
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                <span class="text-[8px] text-slate-400">No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <h4 class="font-bold text-sm text-slate-800 dark:text-slate-100 line-clamp-1 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $novel->title }}</h4>
                        <p class="text-[10px] text-slate-500 line-clamp-1">{{ $novel->author->name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">{{ number_format($novel->view_count) }} views</span>
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
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                <span class="text-[8px] text-slate-400">No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <h4 class="font-bold text-sm text-slate-800 dark:text-slate-100 line-clamp-1 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $novel->title }}</h4>
                        <p class="text-[10px] text-slate-500 line-clamp-1">{{ $novel->author->name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">{{ number_format($novel->view_count) }} views</span>
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
        <div class="w-1.5 h-8 bg-rose-700 rounded-full"></div>
        <h2 class="text-2xl font-bold">Populer Minggu Ini</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($novels->take(6) as $novel)
        <a href="{{ route('novels.show', $novel->slug) }}" class="flex gap-4 p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-slate-400 dark:hover:border-slate-600 transition-all group">
            <div class="w-20 h-28 flex-shrink-0 rounded-xl overflow-hidden shadow-sm">
                @if($novel->cover_image_url)
                    <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                @elseif($novel->cover_image)
                    <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                @else
                    <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2">
                        <span class="text-[10px] text-slate-400 font-bold text-center">{{ $novel->title }}</span>
                    </div>
                @endif
            </div>
            <div class="flex flex-col justify-center">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-slate-900 dark:group-hover:text-white transition-colors line-clamp-1 mb-1">{{ $novel->title }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">{{ $novel->author->name }}</p>
                <div class="flex items-center gap-2">
                    <span class="text-amber-600 flex items-center gap-1 text-xs font-bold">
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
