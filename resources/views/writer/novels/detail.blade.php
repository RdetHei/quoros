@extends('layouts.writer', [
    'active' => 'novels',
    'breadcrumbs' => ['Author Studio', 'My Novels', $novel->title],
    'title' => $novel->title,
    'subtitle' => $novel->alternative_title ?: 'Ringkasan novel, sinopsis, genre, dan karakter.',
    'dashboardTitle' => $novel->title,
    'dashboardSubtitle' => $novel->alternative_title ?: 'Novel overview',
    'currentNovel' => $novel->title,
    'currentNovelId' => $novel->id,
    'novels' => $novels,
    'showCreateBtn' => false,
])

@section('dashboard-content')
@php
    $statusLabels = ['ongoing' => 'Ongoing', 'complete' => 'Completed', 'hiatus' => 'On Hiatus'];
    $typeLabels = ['original' => 'Original Story', 'web_novel' => 'Web Novel', 'light_novel' => 'Light Novel'];
    $ratingLabels = ['everyone' => 'Everyone', 'teen' => 'Teen (13+)', 'mature' => 'Mature (18+)'];

    $publishedCount = (int) ($novel->published_chapters_count ?? 0);
    $totalChapters = (int) ($novel->chapters_count ?? 0);
    $progressPct = $totalChapters > 0 ? (int) round($publishedCount / $totalChapters * 100) : 0;
    $avgRating = (float) ($novel->reviews_avg_rating ?? 0);

    $menuItems = [
        [
            'label' => 'Edit Details',
            'desc' => 'Judul, sinopsis & cover',
            'href' => route('writer.novels.edit', $novel->id),
            'path' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
        ],
        [
            'label' => 'Chapters',
            'desc' => 'Draft & terbitkan bab',
            'href' => route('writer.novels.workspace', $novel->id),
            'path' => 'M4 6h16M4 12h16M4 18h10',
        ],
        [
            'label' => 'Characters',
            'desc' => 'Codex karakter',
            'href' => route('writer.codex', ['novel_id' => $novel->id]),
            'path' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        ],
        [
            'label' => 'Plot Notes',
            'desc' => 'Catatan alur cerita',
            'href' => route('writer.plot', ['novel_id' => $novel->id]),
            'path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        ],
        [
            'label' => 'Analytics',
            'desc' => 'Statistik pembaca',
            'href' => route('writer.analytics', ['novel_id' => $novel->id]),
            'path' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        ],
        [
            'label' => 'Public Page',
            'desc' => 'Lihat halaman publik',
            'href' => route('novels.show', $novel->slug),
            'external' => true,
            'path' => 'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
        ],
    ];
@endphp

<div class="space-y-6">
    {{-- Hero: cover + identitas --}}
    <div class="rounded-md border border-white/5 bg-[#121212] p-5 sm:p-6">
        <div class="flex flex-col gap-6 md:flex-row">
            <div class="mx-auto shrink-0 md:mx-0">
                <div class="h-40 w-[106px] overflow-hidden rounded-sm border border-white/10 bg-[#0a0a0a]">
                    @if($novel->cover_url)
                        <img src="{{ $novel->cover_url }}" alt="{{ $novel->title }}" class="h-full w-full object-cover">
                    @else
                        <div class="relative flex h-full w-full flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-[#1c1712] via-[#120e09] to-black p-3 text-center">
                            <p class="mb-2 text-[7px] font-bold uppercase tracking-[0.28em] text-[#c7a64a]">{{ strtoupper($novel->author?->name ?? auth()->user()->name) }}</p>
                            <p class="font-serif text-[14px] leading-[1.2] tracking-tight text-[#f2efe8]">{{ $novel->title }}</p>
                            <div class="mt-3 h-[2px] w-6 bg-[#c7a64a]/60"></div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="min-w-0 flex-grow">
                <p class="mb-1.5 text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a]">Novel Overview</p>
                <div class="flex flex-wrap items-center gap-2.5">
                    <h3 class="text-[22px] font-serif font-medium tracking-tight text-[#f2efe8]">{{ $novel->title }}</h3>
                    <span class="inline-flex rounded-sm border border-[#c7a64a]/30 px-2 py-0.5 text-[9px] font-bold uppercase tracking-[0.15em] text-[#c7a64a]">
                        {{ $statusLabels[$novel->status] ?? ucfirst($novel->status) }}
                    </span>
                </div>
                @if($novel->alternative_title)
                    <p class="mt-1 text-[12px] italic text-[#a3a3a3]">{{ $novel->alternative_title }}</p>
                @endif

                <div class="mt-3 flex flex-wrap items-center gap-1.5">
                    @if($novel->type)
                        <span class="inline-flex rounded-sm border border-white/10 px-2 py-0.5 text-[9px] font-bold uppercase tracking-[0.15em] text-[#a3a3a3]">{{ $typeLabels[$novel->type] ?? ucfirst($novel->type) }}</span>
                    @endif
                    @if($novel->content_rating)
                        <span class="inline-flex rounded-sm border border-white/10 px-2 py-0.5 text-[9px] font-bold uppercase tracking-[0.15em] text-[#a3a3a3]">{{ $ratingLabels[$novel->content_rating] ?? ucfirst($novel->content_rating) }}</span>
                    @endif
                    @foreach($novel->genres as $genre)
                        <span class="inline-flex rounded-sm border border-white/10 px-2 py-0.5 text-[9px] font-bold uppercase tracking-[0.15em] text-[#a3a3a3]">{{ $genre->name }}</span>
                    @endforeach
                </div>

                <p class="mt-3.5 max-w-3xl text-[11.5px] leading-relaxed text-[#a3a3a3] line-clamp-2">
                    {{ $novel->description ?: 'Belum ada sinopsis. Tambahkan lewat Edit Details.' }}
                </p>

                <div class="mt-5 max-w-md">
                    <div class="mb-1.5 flex items-center justify-between">
                        <span class="text-[10.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">Progress</span>
                        <span class="text-[12px] font-bold text-[#c7a64a] tabular-nums">{{ $publishedCount }} / {{ $totalChapters }} chapters</span>
                    </div>
                    <div class="h-[4px] w-full overflow-hidden rounded-full bg-white/5">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#c7a64a] to-[#d4b55a]" style="width: {{ $progressPct }}%"></div>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('writer.novels.edit', $novel->id) }}" class="inline-flex items-center gap-2 rounded-sm bg-[#c7a64a] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        Edit Details
                    </a>
                    <a href="{{ route('writer.novels.workspace', $novel->id) }}" class="inline-flex items-center gap-2 rounded-sm border border-white/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10" /></svg>
                        Manage Chapters
                    </a>
                    <a href="{{ route('writer.codex', ['novel_id' => $novel->id]) }}" class="inline-flex items-center gap-2 rounded-sm border border-white/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Characters
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-md border border-white/5 bg-[#121212] p-4.5 transition-all hover:border-[#c7a64a]/20">
            <p class="mb-2.5 text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">Views</p>
            <p class="font-serif text-[30px] font-medium leading-none tracking-tight text-[#f2efe8]">{{ number_format($novel->view_count) }}</p>
            <p class="mt-2.5 text-[11px] text-[#a3a3a3]">Total reads</p>
        </div>
        <div class="rounded-md border border-white/5 bg-[#121212] p-4.5 transition-all hover:border-[#c7a64a]/20">
            <p class="mb-2.5 text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">Bookmarks</p>
            <p class="font-serif text-[30px] font-medium leading-none tracking-tight text-[#f2efe8]">{{ number_format($novel->bookmarks_count ?? 0) }}</p>
            <p class="mt-2.5 text-[11px] text-[#a3a3a3]">On reading shelves</p>
        </div>
        <div class="rounded-md border border-white/5 bg-[#121212] p-4.5 transition-all hover:border-[#c7a64a]/20">
            <p class="mb-2.5 text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">Rating</p>
            <p class="font-serif text-[30px] font-medium leading-none tracking-tight text-[#f2efe8]">{{ number_format($avgRating, 1) }}</p>
            <p class="mt-2.5 text-[11px] text-[#a3a3a3]">{{ number_format($novel->reviews_count ?? 0) }} reviews</p>
        </div>
        <div class="rounded-md border border-white/5 bg-[#121212] p-4.5 transition-all hover:border-[#c7a64a]/20">
            <p class="mb-2.5 text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">Chapters</p>
            <p class="font-serif text-[30px] font-medium leading-none tracking-tight text-[#f2efe8]">{{ $publishedCount }}<span class="text-[18px] text-[#a3a3a3]">/{{ $totalChapters }}</span></p>
            <p class="mt-2.5 text-[11px] text-[#a3a3a3]">Published / total</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
        <div class="min-w-0 space-y-6">
            {{-- Menu pengelolaan --}}
            <div class="rounded-md border border-white/5 bg-[#121212] p-5">
                <p class="mb-1.5 text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a]">Manage</p>
                <h3 class="mb-4 text-[18px] font-serif font-medium tracking-tight text-[#f2efe8]">Everything for this novel</h3>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($menuItems as $item)
                        <a href="{{ $item['href'] }}" @if(!empty($item['external'])) target="_blank" rel="noopener" @endif
                           class="group flex items-center gap-3.5 rounded-sm border border-white/5 bg-[#0a0a0a] p-4 transition-all hover:border-[#c7a64a]/25 hover:bg-white/[0.015]">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-sm border border-white/5 bg-[#121212] text-[#c7a64a] transition-colors group-hover:border-[#c7a64a]/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['path'] }}" /></svg>
                            </span>
                            <span class="min-w-0">
                                <span class="flex items-center gap-1.5 text-[12.5px] font-medium text-[#f2efe8] group-hover:text-[#c7a64a] transition-colors">
                                    {{ $item['label'] }}
                                    @if(!empty($item['external']))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    @endif
                                </span>
                                <span class="mt-0.5 block text-[10.5px] text-[#a3a3a3]">{{ $item['desc'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Sinopsis --}}
            <div class="rounded-md border border-white/5 bg-[#121212] p-5">
                <p class="mb-1.5 text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a]">Synopsis</p>
                <h3 class="mb-4 text-[18px] font-serif font-medium tracking-tight text-[#f2efe8]">The story in short</h3>
                @if($novel->description)
                    <p class="whitespace-pre-line text-[12.5px] leading-relaxed text-[#f2efe8]/90">{{ $novel->description }}</p>
                @else
                    <p class="text-[12px] italic text-[#a3a3a3]">Belum ada sinopsis.</p>
                @endif
            </div>

            {{-- Characters --}}
            <div class="rounded-md border border-white/5 bg-[#121212] p-5">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <p class="mb-1.5 text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a]">Cast</p>
                        <h3 class="text-[18px] font-serif font-medium tracking-tight text-[#f2efe8]">Characters</h3>
                    </div>
                    <a href="{{ route('writer.codex', ['novel_id' => $novel->id]) }}" class="mt-1 inline-flex items-center gap-1.5 text-[10.5px] font-semibold uppercase tracking-[0.18em] text-[#c7a64a] transition-colors hover:text-[#d4b55a]">
                        Manage
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                @if($novel->characters->isEmpty())
                    <div class="rounded-sm border border-white/5 bg-[#0a0a0a] px-5 py-10 text-center">
                        <p class="text-[12.5px] text-[#a3a3a3]">Belum ada karakter untuk novel ini.</p>
                        <a href="{{ route('writer.novels.characters.create', $novel) }}" class="mt-4 inline-flex items-center gap-2 rounded-sm border border-white/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Tambah Karakter
                        </a>
                    </div>
                @else
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach($novel->characters as $character)
                            <a href="{{ route('writer.codex', array_filter(['novel_id' => $novel->id, 'character_id' => $character->id])) }}"
                               class="group flex items-center gap-3.5 rounded-sm border border-white/5 bg-[#0a0a0a] p-3.5 transition-all hover:border-[#c7a64a]/25">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-full border border-white/10 bg-[#121212]">
                                    @if($character->image_url)
                                        <img src="{{ $character->image_url }}" alt="{{ $character->name }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="font-serif text-[16px] text-[#f2efe8]">{{ strtoupper(substr($character->name, 0, 1)) }}</span>
                                    @endif
                                </span>
                                <span class="min-w-0">
                                    <span class="block truncate font-serif text-[15px] text-[#f2efe8] transition-colors group-hover:text-[#c7a64a]">{{ $character->name }}</span>
                                    <span class="mt-0.5 block truncate text-[11px] text-[#a3a3a3]">{{ $character->tagline ?: ($character->role ?: 'Supporting') }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Aside: detail metadata --}}
        <aside class="min-w-0 space-y-6">
            <div class="rounded-md border border-white/5 bg-[#121212] p-5">
                <p class="mb-1.5 text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a]">Details</p>
                <h3 class="mb-4 text-[18px] font-serif font-medium tracking-tight text-[#f2efe8]">Metadata</h3>

                <div class="mb-5">
                    <p class="mb-2 text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Genres</p>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($novel->genres as $genre)
                            <span class="inline-flex rounded-sm border border-[#c7a64a]/25 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-[#c7a64a]">{{ $genre->name }}</span>
                        @empty
                            <span class="text-[11.5px] text-[#a3a3a3]">Belum ada genre.</span>
                        @endforelse
                    </div>
                </div>

                <div class="mb-5">
                    <p class="mb-2 text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Tags</p>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($novel->tags as $tag)
                            <span class="inline-flex rounded-sm border border-white/10 px-2.5 py-1 text-[10px] font-medium text-[#a3a3a3]">#{{ $tag->name }}</span>
                        @empty
                            <span class="text-[11.5px] text-[#a3a3a3]">Belum ada tag.</span>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-3.5 border-t border-white/5 pt-4">
                    <div class="flex items-center justify-between">
                        <p class="text-[12px] text-[#a3a3a3]">Progress</p>
                        <p class="text-[12px] font-bold text-[#c7a64a] tabular-nums">{{ $progressPct }}%</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[12px] text-[#a3a3a3]">Language</p>
                        <p class="text-[12px] font-bold text-[#f2efe8]">{{ $novel->language ? strtoupper($novel->language) : '—' }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[12px] text-[#a3a3a3]">Region</p>
                        <p class="text-[12px] font-bold text-[#f2efe8]">{{ $novel->region ? ucfirst($novel->region) : '—' }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[12px] text-[#a3a3a3]">Created</p>
                        <p class="text-[12px] font-bold text-[#f2efe8]">{{ $novel->created_at?->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[12px] text-[#a3a3a3]">Updated</p>
                        <p class="text-[12px] font-bold text-[#f2efe8]">{{ $novel->updated_at?->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
