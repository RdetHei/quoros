@extends('layouts.app')

@section('content')
<!-- Hero Section / Search -->
<div class="relative mb-12 py-16 px-6 rounded-3xl overflow-hidden shadow-2xl shadow-indigo-200 dark:shadow-none">
    <!-- Video Background -->
    <video autoplay muted playsinline class="absolute inset-0 w-full h-full object-cover">
        <source src="{{ asset('storage/video/2025-09-22-1758551629959.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    
    <!-- Overlay for readability -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
    
    <div class="relative z-10 text-center max-w-2xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">Temukan Petualangan Tanpa Batas</h1>
        <p class="text-indigo-100 mb-8 text-lg">Ribuan novel dari berbagai genre menunggu untuk kamu jelajahi.</p>
        
        <form action="{{ route('home') }}" method="GET" class="relative max-w-xl mx-auto">
            <input type="text" name="search" value="{{ request('search') }}" 
                class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white placeholder-indigo-200 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all shadow-xl"
                placeholder="Cari judul novel atau penulis...">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition-colors shadow-lg">Cari</button>
        </form>
    </div>
</div>

<!-- Filters -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('home') }}" 
           class="px-4 py-2 rounded-full text-sm font-semibold {{ !request('genre') ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:border-indigo-600 dark:hover:border-indigo-400' }} transition-all shadow-sm">
           Semua
        </a>
        @foreach($genres as $genre)
            <a href="{{ route('home', ['genre' => $genre->slug]) }}" 
               class="px-4 py-2 rounded-full text-sm font-semibold {{ request('genre') == $genre->slug ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:border-indigo-600 dark:hover:border-indigo-400' }} transition-all shadow-sm">
               {{ $genre->name }}
            </a>
        @endforeach
    </div>
</div>

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
                    <div class="relative aspect-[3/4] mb-3 rounded-2xl overflow-hidden shadow-md group-hover:shadow-xl group-hover:shadow-indigo-500/20 transition-all duration-300 transform group-hover:-translate-y-2 group-hover:scale-[1.03]">
                        @if($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
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
