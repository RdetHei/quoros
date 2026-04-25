@extends('layouts.app')

@section('content')
<!-- Hero Section / Search -->
<div class="relative mb-12 py-16 px-6 bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl overflow-hidden shadow-2xl shadow-indigo-200 dark:shadow-none">
    <div class="absolute inset-0 bg-grid-white/[0.1] bg-[size:20px_20px]"></div>
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

<!-- Section: Novel Terbaru -->
<div class="mb-16">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-indigo-600 rounded-full"></div>
            <h2 class="text-2xl font-bold">Novel Terbaru</h2>
        </div>
        <a href="#" class="text-indigo-600 dark:text-indigo-400 text-sm font-bold hover:underline">Lihat Semua</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @forelse($novels as $novel)
            <a href="{{ route('novels.show', $novel->slug) }}" class="group">
                <div class="relative aspect-[3/4] mb-3 rounded-2xl overflow-hidden shadow-md group-hover:shadow-xl group-hover:shadow-indigo-500/20 transition-all duration-300 transform group-hover:-translate-y-2 group-hover:scale-[1.03]">
                    @if($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 flex items-center justify-center p-4">
                            <span class="text-slate-400 dark:text-slate-600 font-bold text-center text-sm leading-tight">{{ $novel->title }}</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    @if($novel->genres->isNotEmpty())
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-1 text-[10px] font-bold bg-indigo-600 text-white rounded-md shadow-lg uppercase tracking-wider">{{ $novel->genres->first()->name }}</span>
                        </div>
                    @endif
                </div>
                <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2 leading-snug mb-1">{{ $novel->title }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ $novel->author->name }}</p>
            </a>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 dark:text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <p>Belum ada novel yang tersedia.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $novels->links() }}
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
                        4.8
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
