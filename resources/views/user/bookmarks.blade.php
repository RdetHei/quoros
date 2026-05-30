@extends('layouts.app')

@section('content')
<div class="mb-12">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-2 h-10 bg-rose-600 rounded-full"></div>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Bookmark Saya</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Koleksi novel yang kamu simpan untuk dibaca nanti.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @forelse($bookmarks as $bookmark)
            <div class="group relative">
                <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="block">
                    <div class="relative aspect-[3/4] mb-3 rounded-2xl overflow-hidden shadow-md group-hover:shadow-xl group-hover:shadow-rose-500/10 transition-all duration-300 transform group-hover:-translate-y-2 group-hover:scale-[1.03]">
                        @if($bookmark->novel->cover_image_url)
                            <img src="{{ $bookmark->novel->cover_image_url }}" alt="{{ $bookmark->novel->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png';">
                        @elseif($bookmark->novel->cover_image)
                            <img src="{{ asset('storage/' . $bookmark->novel->cover_image) }}" alt="{{ $bookmark->novel->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png';">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-4">
                                <span class="text-slate-400 font-bold text-center text-xs">{{ $bookmark->novel->title }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-rose-600 transition-colors line-clamp-1 text-sm mb-1">{{ $bookmark->novel->title }}</h3>
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium uppercase tracking-wider truncate">{{ $bookmark->novel->author->name }}</p>
                        <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">{{ $bookmark->read_chapters_count }}/{{ $bookmark->total_chapters }}</span>
                    </div>

                    <!-- Reading Progress Bar -->
                    <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mb-2">
                        <div class="h-full bg-emerald-600 transition-all duration-500" style="width: {{ $bookmark->progress_percentage }}%"></div>
                    </div>
                </a>
                
                <form action="{{ route('bookmarks.toggle', $bookmark->novel->id) }}" method="POST" class="absolute top-2 right-2">
                    @csrf
                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white/90 backdrop-blur-sm text-rose-600 rounded-full shadow-lg hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-20 h-20 bg-slate-100 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                </div>
                <p class="text-slate-500 italic">Belum ada novel yang kamu bookmark.</p>
                <a href="{{ route('home') }}" class="inline-block mt-6 px-8 py-3 bg-slate-900 text-white font-bold rounded-2xl text-sm shadow-xl shadow-slate-200">Cari Novel</a>
            </div>
        @endforelse
    </div>

    @if($bookmarks->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $bookmarks->links() }}
        </div>
    @endif
</div>
@endsection
