@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto my-12 px-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Manajemen Banner Carousel</h1>
            <p class="text-slate-500 dark:text-slate-400">Pilih novel yang ingin ditampilkan di sorotan utama halaman depan.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Active Carousel Novels -->
    <div class="mb-12">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Sedang Ditampilkan ({{ $featuredNovels->count() }})</h2>
        </div>

        @if($featuredNovels->isEmpty())
            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-[2rem] p-12 text-center border-2 border-dashed border-slate-200 dark:border-slate-800">
                <p class="text-slate-500 dark:text-slate-400 font-medium">Belum ada novel yang dipilih. Sistem akan menggunakan novel populer sebagai fallback.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredNovels as $novel)
                    <div class="group relative bg-white dark:bg-slate-900 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all">
                        <div class="aspect-[16/9] overflow-hidden">
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.onerror=null; this.src='/error.png'">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent"></div>
                        </div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-5">
                            <h3 class="text-white font-bold truncate mb-1">{{ $novel->title }}</h3>
                            <p class="text-slate-300 text-xs mb-4">{{ $novel->author->name }}</p>
                            
                            <form action="{{ route('admin.carousel.toggle', $novel->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-white/10 hover:bg-red-500 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all border border-white/20 hover:border-red-400">
                                    Hapus dari Carousel
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Search & Add More -->
    <div class="bg-slate-50 dark:bg-slate-800/30 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Tambahkan Novel</h2>
            
            <form action="{{ route('admin.carousel.index') }}" method="GET" class="relative w-full md:w-80">
                <input type="text" name="search" value="{{ $search }}" 
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all"
                    placeholder="Cari judul novel...">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($availableNovels as $novel)
                <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-indigo-200 dark:hover:border-indigo-800 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-16 rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $novel->title }}</h4>
                            <p class="text-xs text-slate-500">{{ $novel->author->name }}</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.carousel.toggle', $novel->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-600 text-slate-600 dark:text-slate-400 hover:text-white text-xs font-bold rounded-xl transition-all">
                            Tambahkan
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-slate-500 dark:text-slate-400 text-sm italic">Tidak ada novel lain yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $availableNovels->links() }}
        </div>
    </div>
</div>
@endsection
