@extends('layouts.app')

@section('content')
<style>[x-cloak]{display:none!important}</style>

<div class="max-w-6xl mx-auto px-4 py-8 md:py-12"
     x-data="{ tab: 'reading' }">

    <!-- Profile Header -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 mb-8 md:mb-10 relative overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-48 md:w-64 h-48 md:h-64 bg-slate-500/10 rounded-full blur-2xl md:blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-48 md:w-64 h-48 md:h-64 bg-emerald-500/10 rounded-full blur-2xl md:blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-center gap-6 md:gap-10">
            <div class="relative shrink-0">
                <div class="w-28 h-28 md:w-44 md:h-44 rounded-full border-4 border-white dark:border-slate-800 shadow-2xl overflow-hidden bg-slate-50 dark:bg-slate-800 flex items-center justify-center ring-2 ring-slate-200/80 dark:ring-slate-700/80">
                    @if($user->profile_photo_url)
                        <img src="{{ $user->profile_photo_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png';">
                    @elseif($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png';">
                    @else
                        <span class="text-4xl md:text-6xl font-black text-slate-400/20 uppercase">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    @endif
                </div>
                @if($user->role === 'admin')
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 whitespace-nowrap px-3 py-1 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] md:text-[10px] font-black uppercase tracking-[0.15em] rounded-full shadow-lg shadow-slate-900/40 ring-2 ring-white dark:ring-slate-900">
                        Admin
                    </div>
                @elseif($user->role === 'writer')
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 whitespace-nowrap px-3 py-1 bg-emerald-700 text-white text-[9px] md:text-[10px] font-black uppercase tracking-[0.15em] rounded-full shadow-lg shadow-emerald-900/30 ring-2 ring-white dark:ring-slate-900">
                        Writer
                    </div>
                @endif
            </div>

            <div class="flex-grow text-center md:text-left min-w-0">
                <div class="flex flex-col md:flex-row md:items-center md:flex-wrap gap-2 md:gap-3 justify-center md:justify-start mb-1">
                    <h1 class="text-2xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $user->name }}</h1>
                    @if($user->role === 'admin')
                        <span class="inline-flex items-center gap-1.5 self-center md:self-auto px-3 py-1 rounded-full text-[10px] md:text-xs font-black uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            Administrator
                        </span>
                    @elseif($user->role === 'writer')
                        <span class="inline-flex items-center gap-1.5 self-center md:self-auto px-3 py-1 rounded-full text-[10px] md:text-xs font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Writer
                        </span>
                    @endif
                </div>

                <p class="text-slate-600 dark:text-slate-400 font-bold mb-1 text-sm md:text-base">@<span>{{ $user->username ?? $user->id }}</span></p>

                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-4 md:mb-5">
                    <span class="inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Anggota sejak {{ $user->created_at->locale(app()->getLocale())->isoFormat('MMM YYYY') }}
                    </span>
                </p>

                @if($user->bio)
                    <p class="text-slate-600 dark:text-slate-400 max-w-2xl leading-relaxed mb-6 text-sm md:text-base">{{ $user->bio }}</p>
                @else
                    <p class="text-slate-400 dark:text-slate-500 italic mb-6 text-sm">Belum ada bio.</p>
                @endif

                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    @auth
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('settings') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Pengaturan
                            </a>
                            @if(auth()->user()->role === 'writer' || auth()->user()->role === 'admin')
                                <a href="{{ route('writer.stats') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all shadow-lg shadow-slate-900/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 012 2h2a2 2 0 012-2" /></svg>
                                    Dashboard Penulis
                                </a>
                            @endif
                        @endif
                    @endauth

                    <div class="px-4 md:px-5 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-slate-700/80 min-w-[7rem]">
                        <span class="block text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Reviews</span>
                        <span class="text-sm md:text-base font-bold text-slate-900 dark:text-white tabular-nums">{{ $user->reviews_count }}</span>
                    </div>
                    <div class="px-4 md:px-5 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-slate-700/80 min-w-[7rem]">
                        <span class="block text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Bookmarks</span>
                        <span class="text-sm md:text-base font-bold text-slate-900 dark:text-white tabular-nums">{{ $user->bookmarks_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.writer-insights', ['writerStats' => $writerStats, 'isOwner' => $isOwner])

    <!-- Tabs -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-wrap gap-2 sm:gap-3 border-b border-slate-200 dark:border-slate-800 pb-px overflow-x-auto scrollbar-thin">
            <button type="button"
                    @click="tab = 'reading'"
                    :class="tab === 'reading' ? 'border-slate-900 dark:border-white text-slate-900 dark:text-white bg-slate-100/80 dark:bg-slate-800' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200'"
                    class="shrink-0 px-4 py-2.5 rounded-t-xl text-xs md:text-sm font-bold border-b-2 -mb-px transition-colors">
                Reading list
            </button>
            <button type="button"
                    @click="tab = 'reviews'"
                    :class="tab === 'reviews' ? 'border-slate-900 dark:border-white text-slate-900 dark:text-white bg-slate-100/80 dark:bg-slate-800' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200'"
                    class="shrink-0 px-4 py-2.5 rounded-t-xl text-xs md:text-sm font-bold border-b-2 -mb-px transition-colors">
                Ulasan
            </button>
        </div>
    </div>

    <!-- Tab: Reading list -->
    <div x-show="tab === 'reading'" x-cloak class="space-y-4">
        @if(!$canViewReadingList)
            <div class="bg-slate-50 dark:bg-slate-800/80 rounded-3xl p-8 md:p-12 text-center border border-slate-200 dark:border-slate-700">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-slate-200 dark:bg-slate-700 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-10 md:w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white mb-2">Reading list privat</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">Pengguna ini menyembunyikan daftar bacaan dari publik.</p>
            </div>
        @elseif($readingList->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                @foreach($readingList as $bookmark)
                    <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="group block">
                        <div class="relative aspect-[3/4] rounded-xl md:rounded-2xl overflow-hidden mb-2 md:mb-3 bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200/80 dark:ring-slate-700/50 group-hover:-translate-y-1 transition-transform duration-300">
                            @if($bookmark->novel->cover_image)
                                <img src="{{ asset('storage/' . $bookmark->novel->cover_image) }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.onerror=null; this.src='/error.png';">
                            @else
                                <div class="w-full h-full flex items-center justify-center p-3">
                                    <span class="text-[10px] text-slate-400 font-bold text-center leading-snug">{{ $bookmark->novel->title }}</span>
                                </div>
                            @endif
                        </div>
                        <h3 class="font-bold text-xs md:text-sm text-slate-800 dark:text-slate-100 line-clamp-2 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $bookmark->novel->title }}</h3>
                        <p class="text-[10px] md:text-[11px] text-slate-500 line-clamp-1">{{ $bookmark->novel->author->name }}</p>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 dark:bg-slate-800/80 rounded-3xl p-10 md:p-14 text-center border border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-500 dark:text-slate-400 italic">Belum ada novel di daftar bacaan.</p>
            </div>
        @endif
    </div>

    <!-- Tab: Reviews -->
    <div x-show="tab === 'reviews'" x-cloak class="space-y-4">
        @if($reviews->isEmpty())
            <div class="bg-slate-50 dark:bg-slate-800/80 rounded-3xl p-10 md:p-14 text-center border border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-500 dark:text-slate-400 italic">Belum ada ulasan.</p>
            </div>
        @else
            <div class="space-y-4 md:space-y-5">
                @foreach($reviews as $review)
                    <article class="rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 p-5 md:p-6 shadow-sm dark:shadow-none">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                            <div class="min-w-0">
                                <a href="{{ route('novels.show', $review->novel->slug) }}" class="text-base md:text-lg font-bold text-slate-900 dark:text-white hover:text-slate-900 dark:hover:text-white transition-colors line-clamp-2">
                                    {{ $review->novel->title }}
                                </a>
                                <p class="text-xs text-slate-500 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center gap-1 shrink-0 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/25 self-start">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600' }}" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="ml-1 text-xs font-bold text-amber-700 dark:text-amber-300 tabular-nums">{{ number_format($review->rating, 1) }}</span>
                            </div>
                        </div>
                        <p class="text-sm md:text-base text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-4 md:line-clamp-none">
                            {{ \Illuminate\Support\Str::limit($review->content, 400) }}
                        </p>
                        @if(\Illuminate\Support\Str::length($review->content) > 400)
                            <a href="{{ route('novels.show', $review->novel->slug) }}" class="inline-block mt-3 text-xs font-bold text-slate-600 dark:text-slate-400 hover:underline">Buka novel</a>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
