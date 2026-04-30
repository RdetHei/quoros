@extends('layouts.app')

@section('content')

{{-- ===== BREADCRUMB ===== --}}
<nav class="flex items-center gap-2 text-[10px] md:text-sm text-slate-400 dark:text-slate-500 mb-6 md:mb-8 font-medium overflow-x-auto no-scrollbar">
    <a href="{{ route('home') }}" class="hover:text-indigo-500 transition-colors flex-shrink-0">Katalog</a>
    <svg class="w-3 h-3 md:w-3.5 md:h-3.5 text-slate-300 dark:text-slate-700 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span class="text-slate-700 dark:text-slate-200 flex-shrink-0">{{ $novel->title }}</span>
</nav>

{{-- ===== HERO SECTION ===== --}}
<div class="relative mb-8 md:mb-10 rounded-2xl md:rounded-3xl overflow-hidden">

    {{-- Blurred BG --}}
    <div class="absolute inset-0 -z-0">
        @if($novel->cover_image)
            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover scale-105 blur-2xl opacity-30 dark:opacity-20">
        @endif
        <div class="absolute inset-0 bg-gradient-to-b from-white/80 via-white/95 to-white dark:from-slate-950/80 dark:via-slate-950/95 dark:to-slate-950"></div>
    </div>

    <div class="relative z-10 p-5 md:p-10">
        <div class="flex flex-col md:flex-row gap-6 md:gap-12">

            {{-- Cover --}}
            <div class="flex-shrink-0">
                <div class="w-40 md:w-56 lg:w-64 mx-auto md:mx-0">
                    <div class="aspect-[3/4] rounded-xl md:rounded-2xl overflow-hidden shadow-2xl shadow-slate-900/20 dark:shadow-black/40 ring-1 ring-slate-200 dark:ring-slate-700">
                        @if($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}"
                                 class="w-full h-full object-cover"
                                 alt="{{ $novel->title }}">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-6 text-center">
                                <span class="text-slate-400 font-bold text-base leading-snug">{{ $novel->title }}</span>
                            </div>
                        @endif
                    </div>

                    @can('update', $novel)
                    <div class="mt-4 flex flex-col gap-2">
                        <a href="{{ route('writer.novels.edit', $novel->id) }}"
                           class="w-full py-2.5 bg-amber-500 hover:bg-amber-400 text-white font-bold rounded-xl text-sm text-center transition-all shadow-lg shadow-amber-500/25">
                            Edit Novel
                        </a>
                        <a href="{{ route('writer.chapters.create', $novel->id) }}"
                           class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl text-sm text-center transition-all shadow-lg shadow-emerald-500/25">
                            + Chapter Baru
                        </a>
                    </div>
                    @endcan
                </div>
            </div>

            {{-- Info --}}
            <div class="flex-grow min-w-0">

                {{-- Genres --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($novel->genres as $genre)
                    <span class="px-3 py-1 text-[11px] font-bold uppercase tracking-widest rounded-full
                                 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400
                                 border border-indigo-200 dark:border-indigo-700/50">
                        {{ $genre->name }}
                    </span>
                    @endforeach
                </div>

                {{-- Title --}}
                <h1 class="text-2xl md:text-4xl lg:text-[2.6rem] font-extrabold text-slate-900 dark:text-white leading-tight tracking-tight mb-2 md:mb-1.5 text-center md:text-left">
                    {{ $novel->title }}
                </h1>
                @if($novel->alternative_title)
                <p class="text-sm md:text-base text-slate-400 dark:text-slate-500 italic mb-5 text-center md:text-left">{{ $novel->alternative_title }}</p>
                @endif

                {{-- Meta row --}}
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-4 md:gap-x-5 gap-y-2 mb-6 text-xs md:text-sm">
                    <a href="{{ route('profile.show', $novel->author->username ?? $novel->author->id) }}"
                       class="flex items-center gap-2 rounded-lg -mx-1 px-1 py-0.5 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 transition-colors group/author">
                        <div class="w-6 h-6 md:w-7 md:h-7 rounded-full overflow-hidden bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 font-black text-[10px] md:text-xs ring-1 ring-slate-200/60 dark:ring-slate-600/40">
                            @if($novel->author->profile_photo)
                                <img src="{{ asset('storage/' . $novel->author->profile_photo) }}" alt="{{ $novel->author->name }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($novel->author->name, 0, 1) }}
                            @endif
                        </div>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 group-hover/author:text-indigo-600 dark:group-hover/author:text-indigo-400">{{ $novel->author->name }}</span>
                    </a>
                    <div class="flex items-center gap-1.5 text-amber-500 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 md:h-4 md:w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ number_format($novel->rating_avg, 1) }}
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 md:h-4 md:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ number_format($novel->view_count) }}
                    </div>
                    <span class="text-slate-400 dark:text-slate-500">{{ $novel->chapters->count() }} Ch.</span>
                </div>

                {{-- Status badges --}}
                @php
                    $statusMap = [
                        'ongoing'  => ['dot' => 'bg-emerald-500', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'text' => 'text-emerald-700 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-800/50'],
                        'hiatus'   => ['dot' => 'bg-amber-500',   'bg' => 'bg-amber-50 dark:bg-amber-900/20',   'text' => 'text-amber-700 dark:text-amber-400',   'border' => 'border-amber-200 dark:border-amber-800/50'],
                        'complete' => ['dot' => 'bg-indigo-500',  'bg' => 'bg-indigo-50 dark:bg-indigo-900/20',  'text' => 'text-indigo-700 dark:text-indigo-400',  'border' => 'border-indigo-200 dark:border-indigo-800/50'],
                    ];
                    $typeMap = [
                        'web_novel'   => ['dot' => 'bg-amber-500',  'bg' => 'bg-amber-50 dark:bg-amber-900/20',   'text' => 'text-amber-700 dark:text-amber-400',   'border' => 'border-amber-200 dark:border-amber-800/50'],
                        'light_novel' => ['dot' => 'bg-blue-500',   'bg' => 'bg-blue-50 dark:bg-blue-900/20',    'text' => 'text-blue-700 dark:text-blue-400',    'border' => 'border-blue-200 dark:border-blue-800/50'],
                        'original'    => ['dot' => 'bg-purple-500', 'bg' => 'bg-purple-50 dark:bg-purple-900/20', 'text' => 'text-purple-700 dark:text-purple-400', 'border' => 'border-purple-200 dark:border-purple-800/50'],
                    ];
                    $ratingMap = [
                        'everyone' => ['dot' => 'bg-emerald-500', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'text' => 'text-emerald-700 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-800/50'],
                        'teen'     => ['dot' => 'bg-orange-500',  'bg' => 'bg-orange-50 dark:bg-orange-900/20',  'text' => 'text-orange-700 dark:text-orange-400',  'border' => 'border-orange-200 dark:border-orange-800/50'],
                        'mature'   => ['dot' => 'bg-rose-500',    'bg' => 'bg-rose-50 dark:bg-rose-900/20',    'text' => 'text-rose-700 dark:text-rose-400',    'border' => 'border-rose-200 dark:border-rose-800/50'],
                    ];
                    $s = $statusMap[$novel->status] ?? ['dot'=>'bg-slate-400','bg'=>'bg-slate-50 dark:bg-slate-800','text'=>'text-slate-600 dark:text-slate-400','border'=>'border-slate-200 dark:border-slate-700'];
                    $t = $typeMap[$novel->type] ?? ['dot'=>'bg-slate-400','bg'=>'bg-slate-50 dark:bg-slate-800','text'=>'text-slate-600 dark:text-slate-400','border'=>'border-slate-200 dark:border-slate-700'];
                    $r = $ratingMap[$novel->content_rating] ?? ['dot'=>'bg-slate-400','bg'=>'bg-slate-50 dark:bg-slate-800','text'=>'text-slate-600 dark:text-slate-400','border'=>'border-slate-200 dark:border-slate-700'];
                @endphp
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-6">
                    <span class="status-badge {{ $s['dot'] }} {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }}">{{ $novel->status }}</span>
                    <span class="status-badge {{ $t['dot'] }} {{ $t['bg'] }} {{ $t['text'] }} {{ $t['border'] }}">{{ str_replace('_', ' ', $novel->type) }}</span>
                    <span class="status-badge {{ $r['dot'] }} {{ $r['bg'] }} {{ $r['text'] }} {{ $r['border'] }}">{{ $novel->content_rating }}</span>
                </div>

                {{-- Description --}}
                <p class="text-sm md:text-base text-slate-600 dark:text-slate-400 leading-relaxed mb-6 max-w-2xl text-center md:text-left">
                    {{ $novel->description ?: 'Belum ada deskripsi untuk novel ini.' }}
                </p>

                {{-- Tags --}}
                @if($novel->tags->count())
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-1.5 mb-7">
                    @foreach($novel->tags as $tag)
                    <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 px-2.5 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700/60">
                        #{{ $tag->name }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    @if($novel->chapters->isNotEmpty())
                    <a href="{{ route('chapters.show', [$novel->slug, $novel->chapters->first()->slug]) }}"
                       class="inline-flex items-center gap-2 px-7 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-indigo-500/25 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                        Mulai Membaca
                    </a>
                    @else
                    <button disabled class="inline-flex items-center gap-2 px-7 py-3 bg-slate-100 dark:bg-slate-800 text-slate-400 font-bold rounded-xl text-sm cursor-not-allowed">
                        Belum Ada Chapter
                    </button>
                    @endif

                    @auth
                        @php $isBookmarked = Auth::user()->bookmarks()->where('novel_id', $novel->id)->exists(); @endphp
                        <form action="{{ route('bookmarks.toggle', $novel->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-3 rounded-xl text-sm font-semibold transition-all border
                                           {{ $isBookmarked
                                              ? 'bg-rose-500 hover:bg-rose-400 text-white border-rose-500 shadow-lg shadow-rose-500/20'
                                              : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-rose-400 hover:text-rose-500' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                {{ $isBookmarked ? 'Tersimpan' : 'Simpan' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 px-4 py-3 rounded-xl text-sm font-semibold bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-rose-400 hover:text-rose-500 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Simpan
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Detail Info Grid --}}
        <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800/70">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-5">Informasi Detail</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-y-5 gap-x-6">
                @foreach([
                    ['label' => 'Judul Alternatif', 'value' => $novel->alternative_title ?: '-', 'italic' => true],
                    ['label' => 'Jenis Novel',       'value' => strtoupper(str_replace('_', ' ', $novel->type))],
                    ['label' => 'Rating Konten',     'value' => strtoupper($novel->content_rating)],
                    ['label' => 'Total Tayangan',    'value' => number_format($novel->view_count) . ' Views'],
                    ['label' => 'Region',            'value' => $novel->region ?: 'Global'],
                    ['label' => 'Bahasa',            'value' => $novel->language ?: 'Unknown'],
                ] as $info)
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $info['label'] }}</p>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 {{ isset($info['italic']) ? 'italic' : '' }}">{{ $info['value'] }}</p>
                </div>
                @endforeach
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Genre</p>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        {{ $novel->genres->pluck('name')->implode(', ') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@if($novel->characters->isNotEmpty())
<div class="mb-16">
    <div class="flex items-center gap-3 mb-7">
        <div class="w-1 h-6 bg-indigo-500 rounded-full"></div>
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">Karakter Novel</h2>
            <p class="text-xs text-slate-400 mt-0.5">Tokoh penting dalam cerita ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 md:gap-6">
        @foreach($novel->characters as $character)
        <div class="group relative min-h-[350px] md:min-h-[400px] overflow-hidden rounded-3xl border border-white/20 dark:border-slate-700/50 shadow-lg shadow-slate-900/10 hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-500">
            @if($character->image)
                <img src="{{ asset('storage/' . $character->image) }}"
                     alt="{{ $character->name }}"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-slate-200 via-slate-300 to-slate-400 dark:from-slate-800 dark:via-slate-700 dark:to-slate-900"></div>
                <div class="absolute inset-0 flex items-center justify-center text-8xl font-black text-white/20 select-none">
                    {{ substr($character->name, 0, 1) }}
                </div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/45 to-black/10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/15 via-transparent to-violet-500/10 opacity-70"></div>

            <div class="relative h-full flex flex-col justify-end p-6">
                <div class="mb-3 h-px w-16 bg-white/40"></div>
                <h3 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white leading-tight drop-shadow-md">
                    {{ $character->name }}
                </h3>

                @if($character->role)
                <span class="inline-flex w-fit mt-3 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-white text-[11px] font-bold uppercase tracking-wider border border-white/20">
                    {{ $character->role }}
                </span>
                @endif

                <p class="mt-4 text-sm leading-relaxed line-clamp-4 {{ !$character->description ? 'italic text-slate-200/80' : 'text-slate-100/95 drop-shadow-sm' }}">
                    {{ $character->description ?: 'Deskripsi karakter belum ditambahkan.' }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ===== CHAPTERS + REVIEWS ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-8 mb-16">

    {{-- CHAPTERS --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="flex items-center justify-between px-7 py-5 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Daftar Chapter</h2>
                <span class="text-xs font-bold text-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full chapter-count">
                    {{ $novel->chapters->count() }}
                </span>
            </div>
            <div class="flex items-center gap-3">
                <input type="text" id="chapterSearch" placeholder="Cari Chapter..." 
                       class="hidden sm:block w-36 lg:w-44 text-xs px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:text-slate-200">
                <button id="orderToggle" class="flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                    </svg>
                    <span id="orderLabel">Terlama</span>
                </button>
            </div>
        </div>

        @if($lastReading && $lastReading->chapter)
        <a href="{{ route('chapters.show', [$novel->slug, $lastReading->chapter->slug]) }}" 
           class="flex items-center justify-between mx-5 mt-4 px-5 py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-2xl text-white shadow-lg shadow-indigo-500/25 transition-all group">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-80">Lanjutkan Membaca</p>
                    <p class="text-sm font-bold">{{ $lastReading->chapter->title }}</p>
                </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif

        <div class="divide-y divide-slate-50 dark:divide-slate-800/60 mt-4" id="chapterList">
            @forelse($novel->chapters->take(10) as $chapter)
            <div class="chapter-row group flex items-center justify-between px-6 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors" data-chapter-title="{{ strtolower($chapter->title) }}" data-chapter-number="{{ $loop->index + 1 }}">
                <a href="{{ route('chapters.show', [$novel->slug, $chapter->slug]) }}"
                   class="flex-grow flex items-center gap-3 min-w-0">
                    <span class="w-7 h-7 flex-shrink-0 flex items-center justify-center text-xs font-black text-slate-300 dark:text-slate-600 group-hover:text-indigo-400 transition-colors chapter-number">
                        {{ $loop->iteration }}
                    </span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1 chapter-title">
                            {{ $chapter->title }}
                        </p>
                        @can('update', $novel)
                            @if($chapter->status === 'draft')
                                <span class="inline-block mt-0.5 text-[9px] font-bold uppercase tracking-widest px-1.5 py-0.5 bg-slate-200 dark:bg-slate-700 text-slate-500 rounded">Draft</span>
                            @elseif($chapter->status === 'scheduled')
                                <span class="inline-block mt-0.5 text-[9px] font-bold uppercase tracking-widest px-1.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 rounded">
                                    Scheduled · {{ $chapter->published_at->format('d/m/y H:i') }}
                                </span>
                            @endif
                        @endcan
                    </div>
                </a>

                <div class="flex items-center gap-1 ml-4 flex-shrink-0">
                    @can('update', $chapter)
                        <a href="{{ route('writer.chapters.edit', [$novel->id, $chapter->id]) }}"
                           class="p-1.5 text-slate-300 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all"
                           title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </a>
                        <form action="{{ route('writer.chapters.destroy', [$novel->id, $chapter->id]) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Hapus chapter ini?')"
                                    class="p-1.5 text-slate-300 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all"
                                    title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-300 group-hover:text-indigo-400 group-hover:translate-x-0.5 transition-all" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @endcan
                </div>
            </div>
            @empty
            <div class="py-16 text-center text-slate-400 text-sm">Belum ada chapter tersedia.</div>
            @endforelse
        </div>

        @if($novel->chapters->count() > 10)
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
            <button id="loadMoreChapters" class="w-full py-3 text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-xl transition-colors">
                Lihat {{ $novel->chapters->count() - 10 }} Chapter Lainnya
            </button>
        </div>
        @endif
    </div>

    {{-- REVIEWS --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 overflow-hidden self-start">
        <div class="flex items-center gap-3 px-7 py-5 border-b border-slate-100 dark:border-slate-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Ulasan</h2>
            <span class="text-xs font-bold text-rose-500 bg-rose-50 dark:bg-rose-900/30 px-2 py-0.5 rounded-full">
                {{ $novel->reviews->count() }}
            </span>
        </div>

        <div class="p-6">
            {{-- Form / Login prompt --}}
            @auth
            <form action="{{ route('reviews.store', $novel->id) }}" method="POST"
                  class="mb-6 p-4 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-slate-700/50"
                  x-data="{ rating: 0, hover: 0 }">
                @csrf
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Tulis Ulasan</p>
                <div class="mb-3 flex items-center gap-1">
                    <input type="hidden" name="rating" :value="rating">
                    <template x-for="i in 5">
                        <button type="button"
                                @click="rating = i"
                                @mouseenter="hover = i"
                                @mouseleave="hover = 0"
                                class="p-0.5 transition-transform hover:scale-110 outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transition-colors"
                                 :class="(hover >= i || (!hover && rating >= i)) ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700'"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    </template>
                    <span class="ml-1 text-xs text-slate-400" x-text="rating > 0 ? rating + '/5' : ''"></span>
                </div>
                <textarea name="content" rows="3"
                          class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-slate-300 dark:placeholder-slate-600 text-slate-700 dark:text-slate-300 resize-none mb-3"
                          placeholder="Pendapatmu tentang novel ini…"></textarea>
                <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-indigo-500/20 active:scale-[0.98]">
                    Kirim Ulasan
                </button>
            </form>
            @else
            <div class="mb-6 p-5 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/40 rounded-2xl text-center">
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Masuk untuk memberikan ulasan.</p>
                <a href="{{ route('login') }}"
                   class="inline-block px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg text-xs uppercase tracking-wider transition-all">
                    Login
                </a>
            </div>
            @endauth

            {{-- Review list --}}
            <div class="space-y-4">
                @forelse($novel->reviews as $review)
                <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700/40">
                    <div class="flex items-center justify-between mb-2">
                        <a href="{{ route('profile.show', $review->user->username ?? $review->user->id) }}"
                           class="flex items-center gap-2 min-w-0 rounded-lg -mx-1 px-1 py-0.5 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors group/rev">
                            <div class="w-7 h-7 shrink-0 rounded-full overflow-hidden bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">
                                @if($review->user->profile_photo)
                                    <img src="{{ asset('storage/' . $review->user->profile_photo) }}" alt="{{ $review->user->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($review->user->name, 0, 1) }}
                                @endif
                            </div>
                            <span class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate group-hover/rev:text-indigo-600 dark:group-hover/rev:text-indigo-400">{{ $review->user->name }}</span>
                        </a>
                        <div class="flex items-center gap-0.5">
                            @for($i = 0; $i < 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $i < $review->rating ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700' }}" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $review->content }}</p>
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-[11px] text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                        @can('delete', $review)
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Hapus ulasan ini?')"
                                    class="text-[11px] font-bold text-slate-300 hover:text-rose-500 transition-colors uppercase tracking-wider">
                                Hapus
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-400 italic text-center py-8">Belum ada ulasan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ===== NOVEL SERUPA ===== --}}
@if($similarNovels->count() > 0)
<div class="mb-16">
    <div class="flex items-center gap-3 mb-7">
        <div class="w-1 h-6 bg-violet-500 rounded-full"></div>
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">Novel Serupa</h2>
            <p class="text-xs text-slate-400 mt-0.5">Berdasarkan kesamaan genre dan tag</p>
        </div>
    </div>

    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-4 md:gap-6">
        @foreach($similarNovels as $similar)
        <a href="{{ route('novels.show', $similar->slug) }}" class="group">
            <div class="relative aspect-[3/4] rounded-xl overflow-hidden mb-2.5 bg-slate-100 dark:bg-slate-800
                        ring-1 ring-slate-200 dark:ring-slate-700
                        group-hover:-translate-y-1.5 group-hover:shadow-xl group-hover:shadow-indigo-200/40 dark:group-hover:shadow-indigo-900/30
                        transition-all duration-300">
                @if($similar->cover_image)
                    <img src="{{ asset('storage/' . $similar->cover_image) }}"
                         alt="{{ $similar->title }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                @else
                    <div class="w-full h-full flex items-center justify-center p-3">
                        <span class="text-[10px] text-slate-400 text-center leading-snug">{{ $similar->title }}</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                    <span class="text-[10px] font-bold text-white bg-indigo-600 px-2 py-1 rounded-md">Detail</span>
                </div>
            </div>
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug mb-0.5">
                {{ $similar->title }}
            </h3>
            <p class="text-[11px] text-slate-400 line-clamp-1">{{ $similar->author->name }}</p>
        </a>
        @endforeach
    </div>
</div>
@endif

    {{-- Floating "Continue Reading" Button --}}
    @if($lastReading && $lastReading->chapter)
    <a href="{{ route('chapters.show', [$novel->slug, $lastReading->chapter->slug]) }}" 
       class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl shadow-2xl shadow-indigo-500/40 transition-all hover:-translate-y-1 group md:hidden">
        <div class="flex flex-col">
            <span class="text-[10px] font-black uppercase tracking-widest opacity-70 leading-none mb-1">Lanjutkan</span>
            <span class="text-sm font-bold truncate max-w-[150px]">{{ $lastReading->chapter->title }}</span>
        </div>
        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center group-hover:bg-white/30 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </a>
    @endif
@endsection

@push('styles')
<style>
/* Hide scrollbars globally on this page */
* {
    scrollbar-width: none;        /* Firefox */
    -ms-overflow-style: none;     /* IE/Edge */
}
*::-webkit-scrollbar {
    display: none;                /* Chrome/Safari/Opera */
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 9999px;
    border-width: 1px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}
.status-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    flex-shrink: 0;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Data dasar novel
        const novelSlug = @js($novel->slug);
        const novelId = {{ $novel->id }};
        const isAuthorOrAdmin = @can('update', $novel) true @else false @endcan;
        
        // Data chapter (tanpa konten agar ringan)
        @php
            $chaptersData = $novel->chapters->values()->map(function($c) {
                return [
                    'id' => $c->id,
                    'title' => $c->title,
                    'slug' => $c->slug,
                    'status' => $c->status,
                    'published_at' => $c->published_at ? $c->published_at->format('d/m/y H:i') : null,
                ];
            });
        @endphp
        const allChapters = @json($chaptersData);

        const chapterList = document.getElementById('chapterList');
        const loadMoreBtn = document.getElementById('loadMoreChapters');
        const searchInput = document.getElementById('chapterSearch');
        const orderToggle = document.getElementById('orderToggle');
        const orderLabel = document.getElementById('orderLabel');

        if (!chapterList) return;

        let displayedCount = 0;
        let isNewestFirst = false;
        let filteredChapters = [...allChapters];

        function getStatusBadge(chapter) {
            if (!isAuthorOrAdmin) return '';
            
            if (chapter.status === 'draft') {
                return '<span class="inline-block mt-0.5 text-[9px] font-bold uppercase tracking-widest px-1.5 py-0.5 bg-slate-200 dark:bg-slate-700 text-slate-500 rounded">Draft</span>';
            } else if (chapter.status === 'scheduled' && chapter.published_at) {
                return `<span class="inline-block mt-0.5 text-[9px] font-bold uppercase tracking-widest px-1.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 rounded">Scheduled · ${chapter.published_at}</span>`;
            }
            return '';
        }

        function getAuthorControls(chapter) {
            if (!isAuthorOrAdmin) {
                return '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-300 group-hover:text-indigo-400 group-hover:translate-x-0.5 transition-all" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>';
            }
            
            return `
                <a href="/writer/novels/${novelId}/chapters/${chapter.id}/edit" class="p-1.5 text-slate-300 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                </a>
                <form action="/writer/novels/${novelId}/chapters/${chapter.id}" method="POST" class="inline">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" onclick="return confirm('Hapus chapter ini?')" class="p-1.5 text-slate-300 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    </button>
                </form>
            `;
        }

        function renderChapters(chapters, append = false) {
            if (!append) {
                chapterList.innerHTML = '';
                displayedCount = 0;
            }
            
            if (chapters.length === 0 && !append) {
                chapterList.innerHTML = '<div class="py-16 text-center text-slate-400 text-sm">Chapter tidak ditemukan.</div>';
                if (loadMoreBtn) loadMoreBtn.parentElement.style.display = 'none';
                return;
            }

            const fragment = document.createDocumentFragment();
            chapters.forEach((chapter) => {
                // Gunakan ID untuk mencari index asli agar nomor chapter tetap konsisten
                const originalIndex = allChapters.findIndex(c => c.id === chapter.id);
                const displayNumber = originalIndex + 1;
                
                const div = document.createElement('div');
                div.className = 'chapter-row group flex items-center justify-between px-6 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors';
                
                const readUrl = `/novels/${novelSlug}/read/${chapter.slug}`;
                
                div.innerHTML = `
                    <a href="${readUrl}" class="flex-grow flex items-center gap-3 min-w-0">
                        <span class="w-7 h-7 flex-shrink-0 flex items-center justify-center text-xs font-black text-slate-300 dark:text-slate-600 group-hover:text-indigo-400 transition-colors chapter-number">
                            ${displayNumber}
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1 chapter-title">
                                ${chapter.title}
                            </p>
                            ${getStatusBadge(chapter)}
                        </div>
                    </a>
                    <div class="flex items-center gap-1 ml-4 flex-shrink-0">${getAuthorControls(chapter)}</div>
                `;
                fragment.appendChild(div);
                displayedCount++;
            });
            chapterList.appendChild(fragment);

            if (loadMoreBtn) {
                const remaining = filteredChapters.length - displayedCount;
                if (remaining > 0) {
                    loadMoreBtn.parentElement.style.display = 'block';
                    loadMoreBtn.textContent = `Lihat ${remaining} Chapter Lainnya`;
                } else {
                    loadMoreBtn.parentElement.style.display = 'none';
                }
            }
        }

        // Inisialisasi awal
        renderChapters(filteredChapters.slice(0, 10));

        if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', function() {
                        const remainingChapters = filteredChapters.slice(displayedCount);
                        renderChapters(remainingChapters, true);
                    });
                }

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                let base = isNewestFirst ? [...allChapters].reverse() : [...allChapters];
                
                filteredChapters = query === '' 
                    ? base 
                    : base.filter(ch => ch.title.toLowerCase().includes(query));
                
                renderChapters(filteredChapters.slice(0, 10));
            });
        }

        if (orderToggle) {
            orderToggle.addEventListener('click', function() {
                isNewestFirst = !isNewestFirst;
                orderLabel.textContent = isNewestFirst ? 'Terbaru' : 'Terlama';
                filteredChapters.reverse();
                renderChapters(filteredChapters.slice(0, 10));
            });
        }
    } catch (err) {
        console.error('Chapter list error:', err);
    }
});
</script>
@endpush