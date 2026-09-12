@extends('layouts.app')

@section('title', 'Updated — Quoros')
@section('meta_description', 'Latest updated novels on Quoros. Recent chapter releases from all series, sorted by most recent update.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

    {{-- ── PAGE HEADER ──────────────────────────────── --}}
    <div class="mb-10">
        <p class="text-[10px] font-black uppercase tracking-[0.28em] text-neutral-400 mb-2">Latest Updates</p>
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-neutral-900 dark:text-white tracking-tight">Recently Updated</h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Bab terbaru dari semua seri — diurutkan paling baru ke paling lama.
                </p>
            </div>

            {{-- Period filter tabs --}}
            <div class="flex items-center gap-1 p-1 bg-neutral-100 dark:bg-neutral-800/60 rounded-xl border border-neutral-200 dark:border-neutral-700/60 w-fit shrink-0">
                @foreach($periods as $key => $data)
                    @php $isActive = $period === $key; @endphp
                    <a href="{{ route('novels.updated', ['period' => $key]) }}"
                       class="px-3.5 py-1.5 rounded-lg text-[10px] md:text-xs font-bold uppercase tracking-widest transition-all whitespace-nowrap
                              {{ $isActive
                                  ? 'bg-white dark:bg-neutral-700 text-neutral-400 dark:text-neutral-300 shadow-sm'
                                  : 'text-neutral-500 dark:text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-200' }}">
                        {{ $data['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @if($novels->isNotEmpty())
        {{-- ── COUNT ROW ──────────────────────────────────── --}}
        <div class="flex items-center gap-2.5 mb-6">
            <div class="w-1 h-5 bg-neutral-400 rounded-full"></div>
            <span class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                {{ $novels->total() }} novel
            </span>
            <span class="text-sm text-neutral-400 dark:text-neutral-500">diperbarui</span>
        </div>

        {{-- ── NOVEL GRID ──────────────────────────────────── --}}
        {{--
            Desktop  : 2 kolom (tiap card = cover kiri + konten kanan)
            Mobile   : 1 kolom
        --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 md:gap-4">
            @foreach($novels as $novel)
                @php
                    $coverUrl = $novel->cover_image_url
                        ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                @endphp

                <article class="group bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 overflow-hidden hover:border-neutral-300 dark:hover:border-neutral-700 hover:shadow-md dark:hover:shadow-none transition-all duration-200">

                    {{-- ── NOVEL ROW (cover + judul + status) ── --}}
                    <a href="{{ route('novels.show', $novel->slug) }}"
                       class="flex items-center gap-3 px-3.5 pt-3 pb-2.5">

                        {{-- Cover --}}
                        <div class="relative w-9 h-[3.25rem] shrink-0 rounded-md overflow-hidden bg-neutral-100 dark:bg-neutral-800 ring-1 ring-neutral-200/80 dark:ring-neutral-700/80">
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}" alt="{{ $novel->title }}"
                                     class="w-full h-full object-cover" loading="lazy"
                                     onerror="this.onerror=null;this.src='/error.png'">
                            @else
                                <img src="/error.png" alt="" class="w-full h-full object-cover" loading="lazy">
                            @endif
                        </div>

                        {{-- Title + status --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start gap-2 justify-between">
                                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white line-clamp-1 leading-snug group-hover:text-neutral-400 dark:group-hover:text-neutral-400 transition-colors">
                                    {{ $novel->title }}
                                </h3>

                                @if($novel->status === 'ongoing')
                                    <span class="shrink-0 px-1.5 py-px text-[8px] font-bold uppercase tracking-wide rounded bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">Ongoing</span>
                                @elseif($novel->status === 'complete')
                                    <span class="shrink-0 px-1.5 py-px text-[8px] font-bold uppercase tracking-wide rounded bg-neutral-400/10 text-neutral-400 dark:text-neutral-300 border border-emerald-500/20">Complete</span>
                                @elseif($novel->status === 'hiatus')
                                    <span class="shrink-0 px-1.5 py-px text-[8px] font-bold uppercase tracking-wide rounded bg-amber-500/10 text-neutral-400 dark:text-neutral-300 border border-amber-500/20">Hiatus</span>
                                @endif
                            </div>
                        </div>
                    </a>

                    {{-- ── CHAPTER LIST ──────────────────────── --}}
                    @if($novel->chapters->isNotEmpty())
                        <div class="border-t border-neutral-100 dark:border-neutral-800 mx-3.5"></div>
                        <div class="px-3.5 pt-1 pb-2.5 space-y-px">
                            @foreach($novel->chapters->take(3) as $ch)
                                @php $chDt = $ch->published_at ?? $ch->created_at; @endphp
                                <a href="{{ route('chapters.show', [$novel->slug, $ch->slug]) }}"
                                   class="flex items-center justify-between gap-3 px-2 py-1.5 -mx-1 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800/60 transition-colors group/ch">
                                    <span class="text-[11px] font-medium text-neutral-500 dark:text-neutral-400 truncate group-hover/ch:text-neutral-400 dark:group-hover/ch:text-neutral-400 transition-colors">
                                        {{ $ch->title }}
                                    </span>
                                    <span class="text-[10px] text-neutral-400 dark:text-neutral-500 shrink-0 tabular-nums">
                                        {{ $chDt ? \Illuminate\Support\Carbon::parse($chDt)->diffForHumans(null, true) : '' }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </article>
            @endforeach
        </div>

        {{-- ── PAGINATION ──────────────────────────────────── --}}
        @if($novels->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $novels->links() }}
            </div>
        @endif

    @else
        {{-- ── EMPTY STATE ─────────────────────────────────── --}}
        <div class="py-24 flex flex-col items-center text-center">
            <div class="w-16 h-16 rounded-2xl bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-neutral-400 dark:text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-neutral-900 dark:text-white mb-2">Belum ada pembaruan</h3>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 max-w-xs">
                @if($period !== 'all')
                    Tidak ada update untuk periode "{{ $periods[$period]['label'] }}".
                    <a href="{{ route('novels.updated') }}" class="text-neutral-400 dark:text-neutral-300 hover:underline font-medium">Lihat semua periode</a>
                @else
                    Bab-bab baru dari novel akan tampil di sini segera setelah diterbitkan.
                @endif
            </p>
        </div>
    @endif

</div>
@endsection