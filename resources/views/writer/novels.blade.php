@extends('layouts.writer', [
    'active' => $active ?? 'novels',
])

@section('dashboard-content')

@php
    function statusBadge($status) {
        $map = [
            'serializing' => ['label' => 'Serializing', 'cls' => 'border-[#c7a64a]/30 text-[#c7a64a]'],
            'completed'   => ['label' => 'Completed',   'cls' => 'border-emerald-500/30 text-emerald-400'],
            'draft'       => ['label' => 'Private Draft','cls' => 'border-white/10 text-[#a3a3a3]'],
        ];
        return $map[$status] ?? $map['serializing'];
    }
    $statusCounts = ['all' => $novels->count(), 'serializing' => 1, 'completed' => 1, 'draft' => 1];
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        @foreach([
            ['All novels', $statusCounts['all'], true],
            ['Serializing', $statusCounts['serializing'], false],
            ['Completed', $statusCounts['completed'], false],
            ['Drafts', $statusCounts['draft'], false],
        ] as [$label, $count, $act])
            <button class="inline-flex items-center gap-1.5 rounded-sm border {{ $act ? 'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' : 'border-white/5 bg-[#121212] text-[#a3a3a3] hover:text-[#f2efe8] hover:border-white/10' }} px-3 py-1.5 text-[11px] font-semibold tracking-wide transition-all">
                {{ $label }}
                <span class="text-[10px] opacity-70 tabular-nums">· {{ $count }}</span>
            </button>
        @endforeach
    </div>
    <div class="flex items-center gap-2.5">
        <span class="text-[10.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Sort by</span>
        <button class="inline-flex items-center gap-2 rounded-md border border-white/10 bg-[#121212] px-3.5 py-2 text-[11px] font-semibold text-[#f2efe8] transition-all hover:border-white/20">
            Last Updated
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
        </button>
    </div>
</div>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3 mb-6">
    @php
        $fakeNovels = [
            [
                'title' => $novels->first()->title ?? 'The Glass Orchard',
                'genre' => 'Historical Fiction',
                'desc' => 'A homecoming, a brass key, and a house that refuses to forget.',
                'words' => number_format($stats['totalWords'] ?? 68420),
                'chapters' => $chapterCount ?? 24,
                'published' => '11 OF 24 CHAPTERS PUBLISHED',
                'pct' => 46,
                'status' => 'serializing',
                'primary_btn' => ['label' => 'Continue Writing', 'icon' => 'pen', 'variant' => 'gold'],
                'secondary_btn' => ['label' => 'Details', 'icon' => 'book'],
                'border_gold' => true,
                'author' => strtoupper(auth()->user()->name),
            ],
            [
                'title' => 'A House of Salt',
                'genre' => 'Historical Fiction',
                'desc' => 'On a remote coast, three generations keep the same dangerous promise.',
                'words' => '82,160',
                'chapters' => 30,
                'published' => '30 OF 30 CHAPTERS PUBLISHED',
                'pct' => 100,
                'status' => 'completed',
                'primary_btn' => ['label' => 'Manage Novel', 'icon' => 'settings', 'variant' => 'ghost'],
                'secondary_btn' => ['label' => 'Details', 'icon' => 'book'],
                'author' => strtoupper(auth()->user()->name),
                'published_date' => 'Published Jun 18, 2026',
            ],
            [
                'title' => 'Letters from the Ashes',
                'genre' => 'Historical Fiction',
                'desc' => 'An unfinished correspondence traces a life interrupted by war.',
                'words' => '8,240',
                'chapters' => 4,
                'published' => '0 OF 4 CHAPTERS PUBLISHED',
                'pct' => 0,
                'status' => 'draft',
                'primary_btn' => ['label' => 'Continue Writing', 'icon' => 'pen', 'variant' => 'ghost'],
                'secondary_btn' => ['label' => 'Details', 'icon' => 'book'],
                'author' => strtoupper(auth()->user()->name),
                'edited_date' => 'Edited Sep 26, 2026',
            ],
        ];
    @endphp

    @foreach($fakeNovels as $n)
    @php $badge = statusBadge($n['status']); @endphp
    <div class="rounded-md border {{ $n['border_gold'] ?? false ? 'border-[#c7a64a]/25' : 'border-white/5' }} bg-[#121212] p-5 hover:border-[#c7a64a]/30 transition-all group flex flex-col">
        <div class="relative aspect-[4/3] rounded-sm border border-white/5 bg-gradient-to-b from-[#1a1714] via-[#0f0d0b] to-black flex items-center justify-center overflow-hidden mb-4">
            <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-[#c7a64a]/20 to-transparent"></div>
            <div class="relative w-[52%] h-[85%] rounded-sm border border-[#c7a64a]/25 bg-gradient-to-br from-[#1c1712] via-[#120e09] to-black shadow-2xl flex flex-col items-center justify-center text-center p-3 overflow-hidden">
                <div class="absolute inset-x-2 top-3 border-t border-[#c7a64a]/20"></div>
                <div class="absolute inset-x-2 bottom-3 border-b border-[#c7a64a]/15"></div>
                <p class="text-[7.5px] uppercase tracking-[0.28em] text-[#c7a64a] mb-3 font-bold">{{ $n['author'] }}</p>
                <p class="font-serif text-[17px] md:text-[18px] text-[#f2efe8] leading-[1.15] tracking-tight mb-4">{{ $n['title'] }}</p>
                <div class="w-8 h-[2px] bg-[#c7a64a]/60 my-auto"></div>
                <p class="mt-auto text-[7px] uppercase tracking-[0.3em] text-[#a3a3a3] font-semibold">A NOVEL</p>
            </div>
        </div>

        <div class="flex items-start justify-between gap-2 mb-2">
            <span class="inline-flex rounded-sm border {{ $badge['cls'] }} px-2 py-0.5 text-[9px] font-bold uppercase tracking-[0.15em]">
                {{ $badge['label'] }}
            </span>
            <button class="text-[#a3a3a3] hover:text-[#f2efe8] transition-colors p-1 -m-1 rounded-sm hover:bg-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
            </button>
        </div>

        <h4 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight mb-1">{{ $n['title'] }}</h4>
        <p class="text-[10.5px] font-semibold uppercase tracking-[0.22em] text-[#a3a3a3] mb-3">{{ $n['genre'] }}</p>
        <p class="text-[12.5px] text-[#a3a3a3] leading-relaxed mb-4 flex-1">{{ $n['desc'] }}</p>

        <div class="border-t border-white/5 pt-4 mb-4">
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <p class="text-[22px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight mb-1">{{ $n['words'] }}</p>
                    <p class="text-[9.5px] uppercase tracking-[0.2em] text-[#a3a3a3] font-semibold">Words</p>
                </div>
                <div>
                    <p class="text-[22px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight mb-1">{{ $n['chapters'] }}</p>
                    <p class="text-[9.5px] uppercase tracking-[0.2em] text-[#a3a3a3] font-semibold">Chapters</p>
                </div>
            </div>
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">{{ $n['published'] }}</p>
            </div>
            <div class="h-[3px] w-full rounded-full bg-white/5 overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-[#c7a64a] to-[#d4b55a]" style="width: {{ $n['pct'] }}%"></div>
            </div>
            @if(!empty($n['published_date']))
                <p class="mt-3 text-[10.5px] text-[#a3a3a3]">{{ $n['published_date'] }}</p>
            @endif
            @if(!empty($n['edited_date']))
                <p class="mt-3 text-[10.5px] text-[#a3a3a3]">{{ $n['edited_date'] }}</p>
            @endif
        </div>

        <div class="flex flex-wrap gap-2.5 mt-auto">
            @if($n['primary_btn']['variant'] === 'gold')
                <a href="{{ route('writer.chapters') }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-md bg-[#c7a64a] px-3.5 py-2.5 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a] min-w-0">
                    @if($n['primary_btn']['icon'] === 'pen')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    @endif
                    <span class="truncate">{{ $n['primary_btn']['label'] }}</span>
                </a>
            @else
                <button class="flex-1 inline-flex items-center justify-center gap-2 rounded-md border border-white/10 px-3.5 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5 min-w-0">
                    @if($n['primary_btn']['icon'] === 'pen')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    @elseif($n['primary_btn']['icon'] === 'settings')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543-.94-3.31.826-2.37 2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    @endif
                    <span class="truncate">{{ $n['primary_btn']['label'] }}</span>
                </button>
            @endif
            <button class="inline-flex items-center justify-center gap-2 rounded-md border border-white/10 px-3.5 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 016.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" /></svg>
                Details
            </button>
        </div>
    </div>
    @endforeach
</div>

<div class="grid gap-4 md:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">

    <div class="rounded-md border border-white/5 bg-[#121212] p-5">
        <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">{{ strtoupper($novels->first()->title ?? 'The Glass Orchard') }}</p>
        <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight mb-5">Your publishing desk</h3>

        <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_minmax(0,320px)]">
            <div>
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Next to Publish</p>
                <div class="rounded-sm border border-white/5 bg-[#0a0a0a] p-4.5 mb-3.5">
                    <h4 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight mb-1.5">12 · The Language of Frost</h4>
                    <p class="text-[11.5px] text-[#a3a3a3]">1,284 words · Draft · No release scheduled</p>
                </div>
                <div class="flex flex-wrap gap-2.5">
                    <button class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        Open Chapter
                    </button>
                    <button class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Schedule
                    </button>
                </div>
            </div>

            <div class="rounded-sm border border-white/5 bg-[#0a0a0a] p-4.5">
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3] mb-3">Library Summary</p>
                <div class="space-y-2.5 mb-3.5">
                    <p class="text-[12.5px] text-[#f2efe8]"><span class="text-[#c7a64a] font-bold">{{ $librarySummary['total_published'] }}</span> published chapters</p>
                    <p class="text-[12.5px] text-[#f2efe8]"><span class="text-[#c7a64a] font-bold">{{ $librarySummary['total_words'] }}</span></p>
                </div>
                <p class="text-[10.5px] text-[#a3a3a3]">{{ $librarySummary['note'] }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-md border border-white/5 bg-[#121212] p-5 flex flex-col">
        <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">An Empty Page Awaits</p>
        <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight mb-3">The next story starts here.</h3>
        <p class="text-[12.5px] text-[#a3a3a3] leading-relaxed mb-5 flex-1">Give a new idea a home. Add a title, collect your notes, and begin when you're ready.</p>
        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[#c7a64a] px-4 py-2.5 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                Create a Novel
            </a>
            <span class="inline-flex rounded-sm border border-white/10 px-2.5 py-2.5 text-[9.5px] font-bold uppercase tracking-[0.18em] text-[#a3a3a3]">
                Private by Default
            </span>
        </div>
    </div>

</div>

@endsection
