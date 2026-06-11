@extends('layouts.app')

@section('title', 'Pencarian Novel — Quoros')
@section('meta_description', 'Temukan novel favorit Anda dengan fitur pencarian lanjutan. Filter berdasarkan genre, status, tipe, dan rating untuk mendapatkan bacaan terbaik di Quoros.')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ===== SEARCH HERO (selaras navbar & katalog: slate + emerald) ===== --}}
    <div class="relative mb-10 md:mb-14 rounded-2xl md:rounded-3xl overflow-hidden bg-slate-900 border border-slate-800 p-6 md:p-10 lg:p-12">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(16,185,129,0.12)_0%,_transparent_55%)] pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,_rgba(16,185,129,0.06)_0%,_transparent_55%)] pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl mx-auto text-center">
            <p class="text-[10px] md:text-xs font-black uppercase tracking-[0.28em] text-emerald-400 mb-3 md:mb-4">Advanced Search</p>
            <h1 class="text-2xl md:text-4xl font-extrabold text-white mb-6 md:mb-8 leading-tight">
                Find Your <span class="text-emerald-400">Favorite Novel</span>
            </h1>

            <form action="{{ route('novels.search') }}" method="GET" id="advanced-search-form" class="text-left">
                <div class="relative mb-4 md:mb-5">
                    <div class="absolute left-4 md:left-5 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <label for="adv-search-input" class="sr-only">Search novels</label>
                    <input type="text"
                           name="q"
                           id="adv-search-input"
                           value="{{ $search ?? '' }}"
                           autocomplete="off"
                           placeholder="Novel title, alternative title, or author name..."
                           class="w-full pl-12 md:pl-14 pr-[5.25rem] md:pr-28 py-4 md:py-5
                                  bg-slate-800/80 border border-slate-700 rounded-xl md:rounded-2xl
                                  text-sm md:text-base text-white placeholder-slate-500 shadow-inner shadow-black/20
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 transition-colors">
                    <button type="submit"
                            aria-label="Submit search"
                            class="absolute right-2 top-1/2 -translate-y-1/2 px-4 md:px-6 py-2 md:py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs md:text-sm transition-colors shadow-lg shadow-black/40">
                        Search
                    </button>
                </div>

                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2 md:mb-3 text-left">Filter</p>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                    <div class="relative">
                        <select name="genre" class="w-full appearance-none cursor-pointer pl-3 pr-9 py-2.5 md:py-3 rounded-xl text-xs md:text-sm text-slate-200 bg-slate-800/80 border border-slate-700 hover:border-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/35 focus:border-emerald-500 transition-colors">
                            <option value="">All Genres</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->slug }}" {{ (request('genre') == $genre->slug) ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>

                    <div class="relative">
                        <select name="status" class="w-full appearance-none cursor-pointer pl-3 pr-9 py-2.5 md:py-3 rounded-xl text-xs md:text-sm text-slate-200 bg-slate-800/80 border border-slate-700 hover:border-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/35 focus:border-emerald-500 transition-colors">
                            <option value="">All Status</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                            <option value="hiatus" {{ request('status') == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                        </select>
                        <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>

                    <div class="relative">
                        <select name="type" class="w-full appearance-none cursor-pointer pl-3 pr-9 py-2.5 md:py-3 rounded-xl text-xs md:text-sm text-slate-200 bg-slate-800/80 border border-slate-700 hover:border-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/35 focus:border-emerald-500 transition-colors">
                            <option value="">All Types</option>
                            <option value="original" {{ request('type') == 'original' ? 'selected' : '' }}>Original</option>
                            <option value="web_novel" {{ request('type') == 'web_novel' ? 'selected' : '' }}>Web Novel</option>
                            <option value="light_novel" {{ request('type') == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                        </select>
                        <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>

                    <div class="relative">
                        <select name="tag" class="w-full appearance-none cursor-pointer pl-3 pr-9 py-2.5 md:py-3 rounded-xl text-xs md:text-sm text-slate-200 bg-slate-800/80 border border-slate-700 hover:border-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/35 focus:border-emerald-500 transition-colors">
                            <option value="">All Tags</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->slug }}" {{ request('tag') == $tag->slug ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>

                    <div class="relative">
                        <select name="min_rating" class="w-full appearance-none cursor-pointer pl-3 pr-9 py-2.5 md:py-3 rounded-xl text-xs md:text-sm text-slate-200 bg-slate-800/80 border border-slate-700 hover:border-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/35 focus:border-emerald-500 transition-colors">
                            <option value="">Min. Rating</option>
                            @foreach([4, 3.5, 3, 2.5] as $rating)
                                <option value="{{ $rating }}" {{ (string) request('min_rating') === (string) $rating ? 'selected' : '' }}>≥ {{ $rating }}</option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>

                    <div class="relative">
                        <select name="sort" class="w-full appearance-none cursor-pointer pl-3 pr-9 py-2.5 md:py-3 rounded-xl text-xs md:text-sm text-slate-200 bg-slate-800/80 border border-slate-700 hover:border-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/35 focus:border-emerald-500 transition-colors">
                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Newest</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rating</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Viewed (Total)</option>
                            <option value="trending" {{ request('sort') == 'trending' ? 'selected' : '' }}>Trending (7 Days)</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A–Z</option>
                        </select>
                        <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== RESULTS ===== --}}
    <div>
        {{-- Result Header --}}
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1 md:w-1.5 h-6 md:h-8 bg-emerald-600 rounded-full"></div>
                <div>
                    @if($search ?? false)
                        <h2 class="text-lg md:text-xl font-bold text-white">
                            Results for
                            <span class="text-emerald-400">"{{ $search }}"</span>
                        </h2>
                        <p class="text-[10px] md:text-xs text-slate-500 mt-0.5">{{ $novels->total() }} novels found</p>
                    @else
                        <h2 class="text-lg md:text-xl font-bold text-white">All Novels</h2>
                        <p class="text-[10px] md:text-xs text-slate-500 mt-0.5">{{ $novels->total() }} novels available</p>
                    @endif
                </div>
            </div>

            @if(($search ?? false) || request('genre') || request('status') || request('type') || request('tag'))
                <a href="{{ route('novels.search') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset Filter
                </a>
            @endif
        </div>

        {{-- Grid Results --}}
        @if($novels->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-6">
                @foreach($novels as $novel)
                    <a href="{{ route('novels.show', $novel->slug) }}" class="group block">
                        <div class="relative aspect-[3/4] rounded-xl md:rounded-2xl overflow-hidden mb-2.5 md:mb-3 bg-slate-800
                                    ring-1 ring-slate-700/50
                                    group-hover:-translate-y-1.5 group-hover:shadow-xl group-hover:shadow-emerald-500/10
                                    transition-all duration-300">
                            @if($novel->cover_image_url)
                                <img src="{{ $novel->cover_image_url }}"
                                     alt="{{ $novel->title }} cover"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     width="200" height="267"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='/error.png'">
                            @elseif($novel->cover_image)
                                <img src="{{ asset('storage/' . $novel->cover_image) }}"
                                     alt="{{ $novel->title }} cover"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     width="200" height="267"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='/error.png'">
                            @else
                                <div class="w-full h-full flex items-center justify-center p-3 bg-slate-800">
                                    <span class="text-[9px] text-slate-500 font-bold text-center leading-snug">{{ $novel->title }}</span>
                                </div>
                            @endif

                            {{-- Rating --}}
                            <div class="absolute top-2 right-2 flex items-center gap-1 px-2 py-1 bg-black/70 backdrop-blur-sm rounded-lg border border-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-[10px] font-bold text-white">{{ number_format($novel->rating_avg, 1) }}</span>
                            </div>

                            {{-- Status --}}
                            @php
                                $statusColor = match($novel->status) {
                                    'ongoing'  => 'bg-emerald-500',
                                    'complete' => 'bg-slate-900',
                                    'hiatus'   => 'bg-amber-500',
                                    default    => 'bg-slate-500',
                                };
                            @endphp
                            <div class="absolute top-2 left-2">
                                <span class="inline-block w-2 h-2 rounded-full {{ $statusColor }} ring-2 ring-black/40"></span>
                            </div>

                            {{-- Hover overlay --}}
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                                <span class="text-[10px] font-bold text-white bg-emerald-600 px-2 py-1 rounded-md">Lihat Detail</span>
                            </div>
                        </div>

                        <h3 class="text-xs md:text-sm font-bold text-slate-100 group-hover:text-emerald-400 transition-colors line-clamp-2 leading-snug mb-0.5">
                            {{ $novel->title }}
                        </h3>
                        <p class="text-[10px] md:text-[11px] text-slate-500 line-clamp-1">{{ $novel->author->name }}</p>

                        @if($novel->genres->count())
                            <div class="flex flex-wrap gap-1 mt-1.5">
                                @foreach($novel->genres->take(2) as $genre)
                                    <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-wider text-emerald-400 bg-emerald-900/30 px-1.5 py-0.5 rounded border border-emerald-800/50">
                                        {{ $genre->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($novels->hasPages())
                <div class="mt-10 md:mt-14 flex justify-center">
                    {{ $novels->links() }}
                </div>
            @endif

        @else
            <div class="py-20 md:py-32 text-center">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-slate-800/60 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl md:text-2xl font-bold text-white mb-2">No results found</h3>
                <p class="text-sm text-slate-500 mb-8 max-w-sm mx-auto">
                    Try different keywords, or remove some filters to get more results.
                </p>
                <a href="{{ route('novels.search') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear All Filters
                </a>
            </div>
        @endif
    </div>
</div>
@endsection