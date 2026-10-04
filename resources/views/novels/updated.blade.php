@extends('layouts.app')

@section('title', 'Latest Updates — Quoros')
@section('meta_description', 'Jelajahi bab terbaru dari semua novel di Quoros, tersusun berdasarkan waktu rilis.')

@push('styles')
<style>
    .updates-page { --archive-gold: #c7a64a; }
    .updates-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
    .updates-filter {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23c7a64a' stroke-width='1.8'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 13px center;
    }
    .updates-card { transition: border-color .2s ease, background-color .2s ease, transform .2s ease; }
    .updates-card:hover { transform: translateY(-1px); }
    .updates-card[hidden], .updates-day[hidden] { display: none !important; }
    .updates-filter option { background: #171717; color: #eee; }
    #updates-sort { color-scheme: dark; }
    #updates-sort option { background: #171717; color: #e5e5e5; }
    @media (prefers-reduced-motion: reduce) { .updates-card, .updates-card:hover { transition: none; transform: none; } }
</style>
@endpush

@section('content')
<main class="updates-page mx-auto w-full max-w-[1240px] px-4 pb-16 pt-24 sm:px-6 lg:px-8">
    <header class="mb-8 border-b border-white/10 pb-7 sm:mb-9 sm:pb-8">
        <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
            <div>
                <p class="mb-2 text-[9px] font-semibold uppercase tracking-[.24em] text-[#c7a64a]">Chronicle <span class="mx-1 text-neutral-600">/</span> Live Archive</p>
                <h1 class="updates-serif text-[2.5rem] font-semibold uppercase leading-none tracking-[.015em] text-[#f2efe8] sm:text-5xl">Latest Updates</h1>
                <p class="mt-3 max-w-xl text-xs leading-6 text-neutral-400 sm:text-sm">Bab terbaru dari setiap dunia, tersusun dari rilis paling baru.</p>
            </div>
            <div class="flex shrink-0 flex-col gap-3 sm:items-end">
                <div class="flex items-center gap-2 text-[9px] uppercase tracking-[.14em] text-neutral-500">
                    <span class="relative flex h-1.5 w-1.5"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#c7a64a] opacity-60"></span><span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span></span>
                    <span>Live archive</span>
                </div>
                <div class="font-mono text-[9px] tracking-[.12em] text-neutral-500">{{ now()->format('d M Y') }} <span class="mx-1 text-neutral-700">·</span> UTC+7</div>
                <nav class="flex items-center gap-1 rounded-lg border border-white/10 bg-[#111] p-1" aria-label="Filter periode update">
                    @foreach($periods as $key => $data)
                        <a href="{{ route('novels.updated', ['period' => $key]) }}" @if($period === $key) aria-current="page" @endif
                           class="rounded-md px-2.5 py-1.5 text-[9px] font-medium uppercase tracking-[.1em] transition-colors sm:px-3 {{ $period === $key ? 'bg-[#c7a64a] text-[#111]' : 'text-neutral-500 hover:text-[#e8d39a]' }}">
                            {{ $key === 'all' ? 'All time' : $data['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
        <div class="mt-7 h-px w-40 bg-gradient-to-r from-[#c7a64a] to-transparent sm:mt-8 sm:w-56"></div>
    </header>

    @if($novels->isNotEmpty())
        <section class="mb-7 rounded-xl border border-white/10 bg-[#121212] p-3 sm:mb-8 sm:p-4" aria-label="Filter daftar update">
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-[1.1fr_1.1fr_1fr_1fr_1.4fr_auto]">
                <label class="min-w-0"><span class="mb-1 block px-1 text-[8px] font-medium uppercase tracking-[.16em] text-neutral-500">Genre</span>
                    <select id="updates-genre" class="updates-filter h-10 w-full rounded-md border border-white/10 bg-[#171717] px-3 pr-9 text-xs text-neutral-200 outline-none focus:border-[#c7a64a]/70"><option value="">All genres</option></select>
                </label>
                <label class="min-w-0"><span class="mb-1 block px-1 text-[8px] font-medium uppercase tracking-[.16em] text-neutral-500">Tag</span>
                    <select id="updates-tag" class="updates-filter h-10 w-full rounded-md border border-white/10 bg-[#171717] px-3 pr-9 text-xs text-neutral-200 outline-none focus:border-[#c7a64a]/70"><option value="">All tags</option></select>
                </label>
                <label class="min-w-0"><span class="mb-1 block px-1 text-[8px] font-medium uppercase tracking-[.16em] text-neutral-500">Status</span>
                    <select id="updates-status" class="updates-filter h-10 w-full rounded-md border border-white/10 bg-[#171717] px-3 pr-9 text-xs text-neutral-200 outline-none focus:border-[#c7a64a]/70"><option value="">All statuses</option></select>
                </label>
                <label class="min-w-0"><span class="mb-1 block px-1 text-[8px] font-medium uppercase tracking-[.16em] text-neutral-500">Region</span>
                    <select id="updates-region" class="updates-filter h-10 w-full rounded-md border border-white/10 bg-[#171717] px-3 pr-9 text-xs text-neutral-200 outline-none focus:border-[#c7a64a]/70"><option value="">All regions</option></select>
                </label>
                <label class="min-w-0"><span class="mb-1 block px-1 text-[8px] font-medium uppercase tracking-[.16em] text-neutral-500">Search this page</span>
                    <span class="flex h-10 items-center gap-2 rounded-md border border-white/10 bg-[#171717] px-3 focus-within:border-[#c7a64a]/70">
                        <svg class="h-3.5 w-3.5 shrink-0 text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
                        <input id="updates-search" type="search" placeholder="Title, author, chapter…" class="min-w-0 flex-1 bg-transparent text-xs text-neutral-200 outline-none placeholder:text-neutral-600" autocomplete="off">
                        <kbd class="hidden rounded border border-white/10 px-1 text-[9px] text-neutral-600 sm:inline">/</kbd>
                    </span>
                </label>
                <button id="updates-clear" type="button" class="mt-4 h-10 whitespace-nowrap px-2 text-[9px] font-medium uppercase tracking-[.12em] text-[#c7a64a] transition hover:text-[#ead79f]" aria-label="Hapus semua filter">Clear</button>
            </div>
        </section>

        <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-white/[.07] pb-3 text-[9px] uppercase tracking-[.13em] text-neutral-500">
            <p>Showing <span id="updates-visible-count" class="text-neutral-300">{{ $novels->count() }}</span> of {{ $novels->total() }} updates <span class="normal-case tracking-normal text-neutral-600">· filters apply to this page</span></p>
            <label class="flex items-center gap-2">Sort
                <select id="updates-sort" class="bg-transparent text-[9px] font-medium uppercase tracking-[.12em] text-neutral-300 outline-none">
                    <option value="newest">Newest first ↓</option><option value="oldest">Oldest first ↑</option>
                </select>
            </label>
        </div>

        @php $currentUpdateDate = null; @endphp
        <div id="updates-groups" class="space-y-8 sm:space-y-9">
            @foreach($novels as $novel)
                @php
                    $latestChapter = $novel->chapters->first();
                    $releaseAt = $latestChapter ? ($latestChapter->published_at ?? $latestChapter->created_at) : ($novel->chapters_max_published_at ?? $novel->chapters_max_created_at);
                    $releaseCarbon = $releaseAt ? \Illuminate\Support\Carbon::parse($releaseAt) : null;
                    $releaseDate = $releaseCarbon?->toDateString() ?? 'unknown';
                    $coverUrl = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                    $genreNames = $novel->genres->pluck('name')->values();
                    $tagNames = $novel->tags->pluck('name')->values();
                    $authorName = $novel->author?->name ?? 'Unknown author';
                @endphp

                @if($releaseDate !== $currentUpdateDate)
                    @if($currentUpdateDate !== null)
                        </div></section>
                    @endif
                    @php $currentUpdateDate = $releaseDate; @endphp
                    <section class="updates-day" data-date-group="{{ $releaseDate }}">
                        <header class="mb-3 flex items-center gap-3 sm:mb-3.5">
                            <h2 class="updates-serif shrink-0 text-lg font-semibold uppercase tracking-[.04em] text-[#eeeae1] sm:text-xl">
                                @if($releaseCarbon?->isToday()) Today @elseif($releaseCarbon?->isYesterday()) Yesterday @elseif($releaseCarbon) {{ $releaseCarbon->format('d M Y') }} @else Archive @endif
                            </h2>
                            <span class="shrink-0 font-mono text-[8px] uppercase tracking-[.12em] text-neutral-600">{{ $releaseCarbon?->format('d M Y') ?? '—' }}</span>
                            <span class="h-px flex-1 bg-white/[.09]"></span>
                            <span class="updates-day-count shrink-0 text-[8px] uppercase tracking-[.12em] text-neutral-600">Releases</span>
                        </header>
                        <div class="space-y-2">
                @endif

                @if($latestChapter)
                    <article class="updates-card group rounded-lg border border-white/[.10] bg-[#131313] p-3 hover:border-[#c7a64a]/35 hover:bg-[#171717] sm:p-3.5"
                             data-title="{{ $novel->title }}" data-author="{{ $authorName }}" data-chapter="{{ $latestChapter->title }}"
                             data-status="{{ $novel->status ?? '' }}" data-region="{{ $novel->region ?? '' }}"
                             data-genres="{{ $genreNames->toJson() }}" data-tags="{{ $tagNames->toJson() }}"
                             data-release="{{ $releaseCarbon?->timestamp ?? 0 }}">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <a href="{{ route('novels.show', $novel->slug) }}" class="h-[4.65rem] w-[3.5rem] shrink-0 overflow-hidden rounded border border-white/10 bg-[#202020] sm:h-[4.9rem] sm:w-[3.65rem]" tabindex="-1" aria-hidden="true">
                                @if($coverUrl)
                                    <img src="{{ $coverUrl }}" alt="" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.04]" loading="lazy" onerror="this.onerror=null;this.src='/error.png'">
                                @else
                                    <img src="/error.png" alt="" class="h-full w-full object-cover" loading="lazy">
                                @endif
                            </a>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                    <a href="{{ route('novels.show', $novel->slug) }}" class="updates-serif line-clamp-1 text-lg font-semibold uppercase leading-tight tracking-[.015em] text-[#eeeae1] transition-colors group-hover:text-[#e2c56f] sm:text-xl">{{ $novel->title }}</a>
                                    @if($releaseCarbon?->isToday()) <span class="rounded-sm bg-[#c7a64a] px-1.5 py-0.5 text-[7px] font-bold uppercase tracking-[.08em] text-[#15130d]">New</span> @endif
                                </div>
                                <p class="mt-1 truncate text-[8px] uppercase tracking-[.13em] text-neutral-500">By {{ $authorName }}</p>
                                <div class="mt-2 flex min-w-0 flex-wrap items-center gap-x-2 gap-y-1.5">
                                    <a href="{{ route('chapters.show', [$novel->slug, $latestChapter->route_identifier]) }}" class="shrink-0 text-[9px] font-semibold uppercase tracking-[.08em] text-[#c7a64a] hover:text-[#ead79f]">Chapter {{ $latestChapter->order ?: $latestChapter->id }}</a>
                                    <span class="h-px w-5 shrink-0 bg-white/20"></span>
                                    <span class="min-w-0 truncate text-[10px] text-neutral-400">{{ $latestChapter->title }}</span>
                                </div>
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach($genreNames->take(1) as $genreName)<span class="rounded-sm border border-white/[.10] px-1.5 py-0.5 text-[7px] uppercase tracking-[.08em] text-neutral-500">{{ $genreName }}</span>@endforeach
                                    @if($novel->status)<span class="rounded-sm border border-white/[.10] px-1.5 py-0.5 text-[7px] uppercase tracking-[.08em] text-neutral-500">{{ str_replace('_', ' ', $novel->status) }}</span>@endif
                                    @if($novel->region)<span class="rounded-sm border border-white/[.10] px-1.5 py-0.5 text-[7px] uppercase tracking-[.08em] text-neutral-500">{{ $novel->region }}</span>@endif
                                </div>
                            </div>
                            <div class="hidden shrink-0 flex-col items-end gap-1.5 text-right sm:flex">
                                <time class="font-mono text-xs font-semibold tabular-nums text-[#e5d7ac]" datetime="{{ $releaseCarbon?->toIso8601String() }}">{{ $releaseCarbon?->format('H:i') ?? '—' }}</time>
                                <span class="text-[8px] uppercase tracking-[.1em] text-neutral-600" data-relative-label="{{ $releaseCarbon?->timestamp ?? 0 }}">{{ $releaseCarbon?->diffForHumans() ?? '' }}</span>
                                <a href="{{ route('chapters.show', [$novel->slug, $latestChapter->route_identifier]) }}" class="mt-0.5 inline-flex items-center gap-1 text-[8px] font-medium uppercase tracking-[.1em] text-neutral-400 transition-colors hover:text-[#d8bb62]">Read chapter <span aria-hidden="true">→</span></a>
                            </div>
                        </div>
                        <div class="mt-2 flex items-center justify-between border-t border-white/[.06] pt-2 sm:hidden">
                            <span class="text-[8px] text-neutral-600" data-relative-label="{{ $releaseCarbon?->timestamp ?? 0 }}">{{ $releaseCarbon?->diffForHumans() ?? '' }}</span>
                            <a href="{{ route('chapters.show', [$novel->slug, $latestChapter->route_identifier]) }}" class="inline-flex items-center gap-1 text-[8px] font-medium uppercase tracking-[.1em] text-[#c7a64a]">Read chapter <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endif
            @endforeach
            @if($currentUpdateDate !== null)</div></section>@endif
        </div>

        <div id="updates-empty-filter" class="hidden rounded-lg border border-white/10 bg-[#121212] px-5 py-16 text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full border border-white/10 text-[#c7a64a]"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg></div>
            <h2 class="updates-serif text-xl uppercase text-[#eeeae1]">No matching updates</h2>
            <p class="mt-2 text-xs text-neutral-500">Coba ubah filter atau kata pencarian.</p>
            <button type="button" data-clear-filters class="mt-5 text-[9px] font-semibold uppercase tracking-[.14em] text-[#c7a64a] hover:text-[#ead79f]">Clear filters</button>
        </div>

        @if($novels->hasPages())
            <nav class="mt-9 flex flex-wrap items-center justify-center gap-1.5" aria-label="Pagination">
                <a href="{{ $novels->previousPageUrl() ?? '#' }}" @if($novels->onFirstPage()) aria-disabled="true" tabindex="-1" @endif class="flex h-9 min-w-9 items-center justify-center rounded border border-white/10 px-3 text-xs text-neutral-400 transition hover:border-[#c7a64a]/60 hover:text-[#e8d39a] {{ $novels->onFirstPage() ? 'pointer-events-none opacity-40' : '' }}" aria-label="Halaman sebelumnya">‹</a>
                @foreach($novels->getUrlRange(max(1, $novels->currentPage() - 2), min($novels->lastPage(), $novels->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}" @if($page === $novels->currentPage()) aria-current="page" @endif class="flex h-9 min-w-9 items-center justify-center rounded border px-3 text-[10px] transition {{ $page === $novels->currentPage() ? 'border-[#c7a64a] bg-[#c7a64a] font-bold text-[#111]' : 'border-white/10 text-neutral-400 hover:border-[#c7a64a]/60 hover:text-[#e8d39a]' }}">{{ $page }}</a>
                @endforeach
                <a href="{{ $novels->nextPageUrl() ?? '#' }}" @if(!$novels->hasMorePages()) aria-disabled="true" tabindex="-1" @endif class="flex h-9 min-w-9 items-center justify-center rounded border border-white/10 px-3 text-xs text-neutral-400 transition hover:border-[#c7a64a]/60 hover:text-[#e8d39a] {{ !$novels->hasMorePages() ? 'pointer-events-none opacity-40' : '' }}" aria-label="Halaman berikutnya">›</a>
            </nav>
        @endif
    @else
        <div class="rounded-lg border border-white/10 bg-[#121212] px-5 py-20 text-center">
            <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full border border-white/10 text-[#c7a64a]"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></div>
            <p class="mb-2 text-[8px] font-semibold uppercase tracking-[.2em] text-[#c7a64a]">Live archive</p>
            <h2 class="updates-serif text-2xl uppercase text-[#eeeae1]">No updates in this period</h2>
            <p class="mx-auto mt-2 max-w-sm text-xs leading-5 text-neutral-500">Bab baru dari novel akan muncul di sini setelah diterbitkan.</p>
            @if($period !== 'all')<a href="{{ route('novels.updated', ['period' => 'all']) }}" class="mt-5 inline-flex rounded border border-[#c7a64a]/40 px-4 py-2 text-[9px] font-semibold uppercase tracking-[.12em] text-[#c7a64a] transition hover:bg-[#c7a64a]/10">Browse all updates</a>@endif
        </div>
    @endif
</main>

@if($novels->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cards = [...document.querySelectorAll('.updates-card')];
    if (!cards.length) return;
    const controls = {
        genre: document.getElementById('updates-genre'), tag: document.getElementById('updates-tag'),
        status: document.getElementById('updates-status'), region: document.getElementById('updates-region'),
        search: document.getElementById('updates-search'), sort: document.getElementById('updates-sort'),
    };
    const normalize = value => (value || '').trim().toLocaleLowerCase();
    const parseList = value => { try { return JSON.parse(value || '[]'); } catch (_) { return []; } };
    const populate = (select, values) => [...new Set(values.filter(Boolean))].sort((a, b) => a.localeCompare(b)).forEach(value => {
        const option = document.createElement('option');
        option.value = normalize(value);
        option.textContent = value;
        select.appendChild(option);
    });
    populate(controls.genre, cards.flatMap(card => parseList(card.dataset.genres)));
    populate(controls.tag, cards.flatMap(card => parseList(card.dataset.tags)));
    populate(controls.status, cards.map(card => card.dataset.status.replaceAll('_', ' ')));
    populate(controls.region, cards.map(card => card.dataset.region));

    const groups = [...document.querySelectorAll('.updates-day')];
    const visibleCount = document.getElementById('updates-visible-count');
    const emptyState = document.getElementById('updates-empty-filter');
    const applyFilters = () => {
        const query = normalize(controls.search.value);
        let visible = 0;
        cards.forEach(card => {
            const genres = parseList(card.dataset.genres).map(normalize);
            const tags = parseList(card.dataset.tags).map(normalize);
            const haystack = normalize([card.dataset.title, card.dataset.author, card.dataset.chapter].join(' '));
            const status = normalize(card.dataset.status).replaceAll('_', ' ');
            const matches = (!query || haystack.includes(query)) && (!controls.genre.value || genres.includes(controls.genre.value))
                && (!controls.tag.value || tags.includes(controls.tag.value)) && (!controls.status.value || status === controls.status.value)
                && (!controls.region.value || normalize(card.dataset.region) === controls.region.value);
            card.hidden = !matches;
            if (matches) visible++;
        });
        groups.forEach(group => {
            const count = [...group.querySelectorAll('.updates-card')].filter(card => !card.hidden).length;
            group.hidden = count === 0;
            const label = group.querySelector('.updates-day-count');
            if (label) label.textContent = `${String(count).padStart(2, '0')} ${count === 1 ? 'release' : 'releases'}`;
        });
        visibleCount.textContent = visible;
        emptyState.classList.toggle('hidden', visible > 0);
    };
    Object.values(controls).forEach(control => {
        control.addEventListener('input', applyFilters);
        control.addEventListener('change', applyFilters);
    });
    const clearFilters = () => {
        controls.genre.value = ''; controls.tag.value = ''; controls.status.value = ''; controls.region.value = ''; controls.search.value = '';
        applyFilters();
    };
    document.getElementById('updates-clear').addEventListener('click', clearFilters);
    document.querySelectorAll('[data-clear-filters]').forEach(button => button.addEventListener('click', clearFilters));

    controls.sort.addEventListener('change', () => {
        const oldest = controls.sort.value === 'oldest';
        const orderedGroups = [...groups].sort((a, b) => a.dataset.dateGroup.localeCompare(b.dataset.dateGroup) * (oldest ? 1 : -1));
        const wrapper = document.getElementById('updates-groups');
        orderedGroups.forEach(group => {
            const list = group.querySelector('.space-y-2');
            [...list.querySelectorAll('.updates-card')]
                .sort((a, b) => (Number(a.dataset.release) - Number(b.dataset.release)) * (oldest ? 1 : -1))
                .forEach(card => list.appendChild(card));
            wrapper.appendChild(group);
        });
    });

    document.addEventListener('keydown', event => {
        if (event.key === '/' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
            event.preventDefault(); controls.search.focus();
        }
        if (event.key === 'Escape' && document.activeElement === controls.search) {
            controls.search.value = ''; applyFilters(); controls.search.blur();
        }
    });
    const updateRelativeTimes = () => {
        const now = Date.now() / 1000;
        document.querySelectorAll('[data-relative-label]').forEach(label => {
            const timestamp = Number(label.dataset.relativeLabel);
            if (!timestamp) return;
            const minutes = Math.max(0, Math.floor((now - timestamp) / 60));
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(minutes / 1440);
            label.textContent = minutes < 1 ? 'Just now' : minutes < 60 ? `${minutes} ${minutes === 1 ? 'minute' : 'minutes'} ago` : minutes < 1440 ? `${hours} ${hours === 1 ? 'hour' : 'hours'} ago` : `${days} ${days === 1 ? 'day' : 'days'} ago`;
        });
    };
    updateRelativeTimes();
    window.setInterval(updateRelativeTimes, 60000);
    applyFilters();
});
</script>
@endif
@endsection
