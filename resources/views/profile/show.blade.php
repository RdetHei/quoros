@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 md:py-12">
    <!-- Profile Header -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-12 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 mb-8 md:mb-12 relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-48 md:w-64 h-48 md:h-64 bg-indigo-500/10 rounded-full blur-2xl md:blur-3xl"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-48 md:w-64 h-48 md:h-64 bg-emerald-500/10 rounded-full blur-2xl md:blur-3xl"></div>

        <div class="relative flex flex-col md:flex-row items-center gap-6 md:gap-12">
            <!-- Profile Photo -->
            <div class="relative">
                <div class="w-28 h-28 md:w-48 md:h-48 rounded-full border-4 border-white dark:border-slate-800 shadow-2xl overflow-hidden bg-indigo-50 dark:bg-slate-800 flex items-center justify-center">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl md:text-7xl font-black text-indigo-600/30 dark:text-indigo-400/20 uppercase">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    @endif
                </div>
                @if($user->role === 'admin')
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-3 md:px-4 py-1 bg-indigo-600 text-white text-[8px] md:text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">Admin</div>
                @elseif($user->role === 'writer')
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-3 md:px-4 py-1 bg-emerald-500 text-white text-[8px] md:text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">Writer</div>
                @endif
            </div>

            <!-- User Info -->
            <div class="flex-grow text-center md:text-left">
                <h1 class="text-2xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-1 md:mb-2">{{ $user->name }}</h1>
                <p class="text-indigo-600 dark:text-indigo-400 font-bold mb-4 text-sm md:text-base">@<span>{{ $user->username ?? $user->id }}</span></p>
                
                @if($user->bio)
                    <p class="text-slate-600 dark:text-slate-400 max-w-xl leading-relaxed mb-6 text-xs md:text-base">{{ $user->bio }}</p>
                @else
                    <p class="text-slate-400 dark:text-slate-500 italic mb-6 text-xs md:text-sm">Belum ada bio.</p>
                @endif

                <div class="flex flex-wrap justify-center md:justify-start gap-3 md:gap-4">
                    <div class="px-4 md:px-6 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <span class="block text-[8px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5 md:mb-1">Bergabung</span>
                        <span class="text-xs md:text-sm font-bold text-slate-900 dark:text-white">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div class="px-4 md:px-6 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <span class="block text-[8px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5 md:mb-1">Reviews</span>
                        <span class="text-xs md:text-sm font-bold text-slate-900 dark:text-white">{{ $user->reviews->count() }}</span>
                    </div>
                    <div class="px-4 md:px-6 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <span class="block text-[8px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5 md:mb-1">Bookmarks</span>
                        <span class="text-xs md:text-sm font-bold text-slate-900 dark:text-white">{{ $user->bookmarks->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reading List Section -->
    <div>
        <div class="flex items-center gap-2 md:gap-3 mb-6 md:mb-8">
            <div class="w-1 md:w-1.5 h-6 md:h-8 bg-indigo-600 rounded-full"></div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white">Reading List</h2>
        </div>

        @if(!$user->is_public_reading_list && Auth::id() !== $user->id)
            <div class="bg-slate-50 dark:bg-slate-800 rounded-3xl p-8 md:p-12 text-center border border-slate-200 dark:border-slate-700">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-10 md:w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white mb-1 md:mb-2">Reading List Privat</h3>
                <p class="text-xs md:text-sm text-slate-500">User ini memilih untuk menyembunyikan daftar bacaannya.</p>
            </div>
        @elseif(count($readingList) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
                @foreach($readingList as $bookmark)
                    <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="group">
                        <div class="relative aspect-[3/4] mb-2 md:mb-3 rounded-xl md:rounded-2xl overflow-hidden shadow-md group-hover:shadow-xl transition-all">
                            @if($bookmark->novel->cover_image)
                                <img src="{{ asset('storage/' . $bookmark->novel->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-4">
                                    <span class="text-[10px] text-slate-400 font-bold text-center">{{ $bookmark->novel->title }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <h3 class="font-bold text-xs md:text-sm text-slate-800 dark:text-slate-100 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $bookmark->novel->title }}</h3>
                        <p class="text-[8px] md:text-[10px] text-slate-500">{{ $bookmark->novel->author->name }}</p>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 dark:bg-slate-800 rounded-3xl p-8 md:p-12 text-center border border-slate-200 dark:border-slate-700">
                <p class="text-xs md:text-sm text-slate-500 italic">Belum ada novel di daftar bacaan.</p>
            </div>
        @endif
    </div>
</div>
@endsection
