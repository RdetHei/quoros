@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Dashboard</h1>
    <p class="text-slate-500 dark:text-slate-400">Selamat datang kembali, <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ Auth::user()->name }}</span>!</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <!-- Stat Cards -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Bacaan</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ Auth::user()->readingHistories()->count() }}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center text-rose-600 dark:text-rose-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Bookmark</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ Auth::user()->bookmarks()->count() }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Ulasan</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ Auth::user()->reviews()->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-10">
    <!-- Bookmark List -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
        <h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
            Bookmark Saya
        </h2>
        <div class="space-y-4">
            @forelse($bookmarks as $bookmark)
                <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all border border-transparent hover:border-slate-100 dark:hover:border-slate-800">
                    <div class="w-12 h-16 flex-shrink-0 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shadow-sm">
                        @if($bookmark->novel->cover_image)
                            <img src="{{ asset('storage/' . $bookmark->novel->cover_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center p-1 text-[8px] font-bold text-slate-400 text-center uppercase">NO COVER</div>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 dark:text-white line-clamp-1">{{ $bookmark->novel->title }}</h4>
                        <p class="text-xs text-slate-500">{{ $bookmark->novel->author->name }}</p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-500 italic py-4">Belum ada novel yang dibookmark.</p>
            @endforelse
        </div>
    </div>

    <!-- Reading History -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
        <h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Riwayat Bacaan
        </h2>
        <div class="space-y-6">
            @forelse($histories as $history)
                <div class="flex items-start gap-4 pb-6 border-b border-slate-50 dark:border-slate-800 last:border-0 last:pb-0 group">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 mt-2"></div>
                    <div class="flex-grow">
                        <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">
                            Melanjutkan <span class="font-bold text-slate-900 dark:text-white">{{ $history->novel->title }}</span>
                        </p>
                        <a href="{{ route('chapters.show', [$history->novel->slug, $history->chapter->slug]) }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline block mt-1">
                            {{ $history->chapter->title }}
                        </a>
                        <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider block mt-1">{{ $history->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500 italic py-4">Belum ada riwayat bacaan.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
    <!-- Role Specific Action -->
    <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl p-8 text-white shadow-xl shadow-indigo-200 dark:shadow-none">
        <h2 class="text-2xl font-bold mb-4 text-white">Akses Penulis</h2>
        <p class="text-indigo-100 mb-8 leading-relaxed text-sm">Kelola novel karyamu, tambahkan chapter baru, dan lihat statistik pembaca langsung dari dashboard penulis.</p>
        
        @if(Auth::user()->role === 'writer' || Auth::user()->role === 'admin')
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('writer.novels.index') }}" class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl text-sm transition-all hover:bg-indigo-50 shadow-lg">Kelola Novel Saya</a>
                <a href="{{ route('writer.novels.create') }}" class="px-6 py-3 bg-indigo-500/30 text-white font-bold rounded-xl text-sm border border-white/20 transition-all hover:bg-indigo-500/40">Buat Novel Baru</a>
            </div>
        @else
            <div class="p-4 bg-white/10 rounded-2xl border border-white/20">
                <p class="text-sm font-medium italic">Kamu saat ini terdaftar sebagai pembaca. Ingin mulai menulis? Hubungi admin untuk upgrade akun.</p>
            </div>
        @endif
    </div>
</div>
@endsection
