@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Search Header -->
    <div class="mb-12 text-center">
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-6">Pencarian Novel</h1>
        
        <form action="{{ route('novels.search') }}" method="GET" class="relative max-w-2xl mx-auto">
            <input type="text" name="q" value="{{ $search }}" 
                class="w-full pl-14 pr-4 py-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] text-lg focus:outline-none focus:ring-4 focus:ring-indigo-500/10 shadow-xl shadow-indigo-100/50 dark:shadow-none transition-all"
                placeholder="Cari judul, judul alternatif, atau penulis...">
            <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 px-8 py-3 bg-indigo-600 text-white font-bold rounded-[1.5rem] hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-none">
                Cari
            </button>
        </form>
    </div>

    <!-- Results Section -->
    <div>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                @if($search)
                    Hasil untuk "{{ $search }}"
                @else
                    Semua Novel
                @endif
                <span class="ml-2 text-sm font-medium text-slate-400">({{ $novels->total() }})</span>
            </h2>
        </div>

        @if($novels->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 md:gap-8">
                @foreach($novels as $novel)
                    <a href="{{ route('novels.show', $novel->slug) }}" class="group">
                        <div class="relative aspect-[2/3] rounded-2xl overflow-hidden mb-3 shadow-md group-hover:shadow-xl group-hover:-translate-y-1 transition-all duration-300">
                            @if($novel->cover_image)
                                <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-4">
                                    <span class="text-xs text-slate-400 font-bold text-center">{{ $novel->title }}</span>
                                </div>
                            @endif
                            
                            <!-- Rating Badge -->
                            <div class="absolute top-2 right-2 px-2 py-1 bg-black/60 backdrop-blur-md rounded-lg flex items-center gap-1 border border-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <span class="text-[10px] font-bold text-white">{{ number_format($novel->reviews->avg('rating') ?? 0, 1) }}</span>
                            </div>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white line-clamp-2 group-hover:text-indigo-600 transition-colors mb-1">{{ $novel->title }}</h3>
                        <p class="text-[11px] text-slate-500 line-clamp-1">Oleh {{ $novel->author->name }}</p>
                    </a>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $novels->links() }}
            </div>
        @else
            <div class="py-20 text-center">
                <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Tidak ada hasil ditemukan</h3>
                <p class="text-slate-500">Coba gunakan kata kunci lain atau periksa ejaanmu.</p>
            </div>
        @endif
    </div>
</div>
@endsection
