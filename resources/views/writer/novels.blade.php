@extends('layouts.writer', [
    'active' => $active ?? 'novels',
])

@section('dashboard-content')

@php
    function statusBadge($state) {
        $map = [
            'serializing' => ['label' => 'Serializing',  'cls' => 'border-[#c7a64a]/30 text-[#c7a64a]'],
            'completed'   => ['label' => 'Completed',    'cls' => 'border-emerald-500/30 text-emerald-400'],
            'hiatus'      => ['label' => 'On Hiatus',    'cls' => 'border-amber-500/30 text-amber-400'],
            'draft'       => ['label' => 'Private Draft','cls' => 'border-white/10 text-[#a3a3a3]'],
        ];
        return $map[$state] ?? $map['draft'];
    }
    $sortLabels = [
        'updated' => 'Last Updated',
        'created' => 'Date Created',
        'title'   => 'Title (A–Z)',
        'words'   => 'Word Count',
        'readers' => 'Readers',
    ];
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3" x-data="{ sortOpen: false }">
    <div class="flex flex-wrap items-center gap-2">
        @foreach([
            ['all', 'All novels', $statusCounts['all']],
            ['serializing', 'Serializing', $statusCounts['serializing']],
            ['completed', 'Completed', $statusCounts['completed']],
            ['hiatus', 'On Hiatus', $statusCounts['hiatus']],
            ['draft', 'Drafts', $statusCounts['draft']],
        ] as [$key, $label, $count])
            <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}"
               class="inline-flex items-center gap-1.5 rounded-sm border {{ $status === $key ? 'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' : 'border-white/5 bg-[#121212] text-[#a3a3a3] hover:text-[#f2efe8] hover:border-white/10' }} px-3 py-1.5 text-[11px] font-semibold tracking-wide transition-all">
                {{ $label }}
                <span class="text-[10px] opacity-70 tabular-nums">· {{ $count }}</span>
            </a>
        @endforeach
    </div>
    <div class="relative flex items-center gap-2.5">
        <span class="text-[10.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Sort by</span>
        <button type="button" @click="sortOpen = !sortOpen"
                class="inline-flex items-center gap-2 rounded-md border border-white/10 bg-[#121212] px-3.5 py-2 text-[11px] font-semibold text-[#f2efe8] transition-all hover:border-white/20">
            {{ $sortLabels[$sort] ?? 'Last Updated' }}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
        </button>
        <div x-show="sortOpen" x-cloak @click.away="sortOpen = false" x-transition.opacity
             class="absolute right-0 top-full z-30 mt-2 w-[200px] overflow-hidden rounded-md border border-white/10 bg-[#121212] shadow-2xl">
            @foreach($sortLabels as $key => $label)
                <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}"
                   class="block px-4 py-2.5 text-[11.5px] font-medium transition-colors {{ $sort === $key ? 'bg-[#c7a64a]/10 text-[#c7a64a]' : 'text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>
</div>

@if($filtered->isEmpty())
    <div class="rounded-md border border-white/5 bg-[#121212] px-6 py-16 text-center mb-6">
        <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full border border-[#c7a64a]/25 bg-[#0a0a0a] text-[#c7a64a]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 016.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" /></svg>
        </div>
        <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight mb-2">
            {{ $shelf->isEmpty() ? 'Your shelf is still empty.' : 'No novels match this filter.' }}
        </h3>
        <p class="mx-auto mb-6 max-w-md text-[12.5px] leading-relaxed text-[#a3a3a3]">
            {{ $shelf->isEmpty() ? 'Begin your first story — give it a title and start writing.' : 'Try another status filter to find your work.' }}
        </p>
        @if($shelf->isEmpty())
            <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[#c7a64a] px-4 py-2.5 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                Create a Novel
            </a>
        @else
            <a href="{{ route('writer.novels') }}" class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                Reset filter
            </a>
        @endif
    </div>
@else
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3 mb-6">
    @foreach($filtered as $n)
    @php $badge = statusBadge($n['state']); @endphp
    <div class="rounded-md border {{ $n['state'] === 'serializing' ? 'border-[#c7a64a]/25' : 'border-white/5' }} bg-[#121212] p-5 hover:border-[#c7a64a]/30 transition-all group flex flex-col">
        <div class="relative aspect-[4/3] rounded-sm border border-white/5 bg-gradient-to-b from-[#1a1714] via-[#0f0d0b] to-black flex items-center justify-center overflow-hidden mb-4">
            <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-[#c7a64a]/20 to-transparent"></div>
            @if($n['cover_url'])
                <img src="{{ $n['cover_url'] }}" alt="{{ $n['title'] }}" class="h-full w-full object-cover">
            @else
                <div class="relative w-[52%] h-[85%] rounded-sm border border-[#c7a64a]/25 bg-gradient-to-br from-[#1c1712] via-[#120e09] to-black shadow-2xl flex flex-col items-center justify-center text-center p-3 overflow-hidden">
                    <div class="absolute inset-x-2 top-3 border-t border-[#c7a64a]/20"></div>
                    <div class="absolute inset-x-2 bottom-3 border-b border-[#c7a64a]/15"></div>
                    <p class="text-[7.5px] uppercase tracking-[0.28em] text-[#c7a64a] mb-3 font-bold">{{ strtoupper(auth()->user()->name) }}</p>
                    <p class="font-serif text-[17px] md:text-[18px] text-[#f2efe8] leading-[1.15] tracking-tight mb-4">{{ $n['title'] }}</p>
                    <div class="w-8 h-[2px] bg-[#c7a64a]/60 my-auto"></div>
                    <p class="mt-auto text-[7px] uppercase tracking-[0.3em] text-[#a3a3a3] font-semibold">A NOVEL</p>
                </div>
            @endif
        </div>

        <div class="flex items-start justify-between gap-2 mb-2">
            <span class="inline-flex rounded-sm border {{ $badge['cls'] }} px-2 py-0.5 text-[9px] font-bold uppercase tracking-[0.15em]">
                {{ $badge['label'] }}
            </span>
            <div class="relative" x-data="{ menuOpen: false }">
                <button type="button" @click="menuOpen = !menuOpen" class="text-[#a3a3a3] hover:text-[#f2efe8] transition-colors p-1 -m-1 rounded-sm hover:bg-white/5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                </button>
                <div x-show="menuOpen" x-cloak @click.away="menuOpen = false" x-transition.opacity
                     class="absolute right-0 top-full z-30 mt-1.5 w-[180px] overflow-hidden rounded-md border border-white/10 bg-[#121212] shadow-2xl">
                    <a href="{{ route('writer.novels.workspace', $n['id']) }}" class="block px-4 py-2.5 text-[11.5px] font-medium text-[#a3a3a3] transition-colors hover:bg-white/5 hover:text-[#f2efe8]">Open workspace</a>
                    <a href="{{ route('writer.novels.edit', $n['id']) }}" class="block px-4 py-2.5 text-[11.5px] font-medium text-[#a3a3a3] transition-colors hover:bg-white/5 hover:text-[#f2efe8]">Edit details</a>
                    @if($n['published'] > 0)
                        <a href="{{ route('novels.show', $n['slug']) }}" class="block px-4 py-2.5 text-[11.5px] font-medium text-[#a3a3a3] transition-colors hover:bg-white/5 hover:text-[#f2efe8]">View public page</a>
                    @endif
                    <form method="POST" action="{{ route('writer.novels.destroy', $n['id']) }}" onsubmit="return confirm('Delete “{{ $n['title'] }}” and all of its chapters? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full px-4 py-2.5 text-left text-[11.5px] font-medium text-red-400/90 transition-colors hover:bg-red-500/10">Delete novel</button>
                    </form>
                </div>
            </div>
        </div>

        <h4 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight mb-1">{{ $n['title'] }}</h4>
        <p class="text-[10.5px] font-semibold uppercase tracking-[0.22em] text-[#a3a3a3] mb-3">{{ $n['genre'] }}</p>
        <p class="text-[12.5px] text-[#a3a3a3] leading-relaxed mb-4 flex-1">{{ \Illuminate\Support\Str::limit($n['desc'], 120) }}</p>

        <div class="border-t border-white/5 pt-4 mb-4">
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <p class="text-[22px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight mb-1">{{ number_format($n['words']) }}</p>
                    <p class="text-[9.5px] uppercase tracking-[0.2em] text-[#a3a3a3] font-semibold">Words</p>
                </div>
                <div>
                    <p class="text-[22px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight mb-1">{{ $n['chapters'] }}</p>
                    <p class="text-[9.5px] uppercase tracking-[0.2em] text-[#a3a3a3] font-semibold">Chapters</p>
                </div>
            </div>
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">{{ $n['published'] }} OF {{ $n['chapters'] }} CHAPTERS PUBLISHED</p>
            </div>
            <div class="h-[3px] w-full rounded-full bg-white/5 overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-[#c7a64a] to-[#d4b55a]" style="width: {{ $n['pct'] }}%"></div>
            </div>
            <p class="mt-3 text-[10.5px] text-[#a3a3a3]">Updated {{ $n['updated_at']->diffForHumans() }} · {{ number_format($n['readers']) }} readers · {{ number_format($n['rating'], 1) }} ★</p>
        </div>

        <div class="flex flex-wrap gap-2.5 mt-auto">
            @if($n['state'] === 'completed')
                <a href="{{ route('writer.novels.workspace', $n['id']) }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-md border border-white/10 px-3.5 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5 min-w-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543-.94-3.31.826-2.37 2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span class="truncate">Manage Novel</span>
                </a>
            @else
                <a href="{{ route('writer.chapters', ['novel_id' => $n['id']]) }}" @class([
                    'flex-1 inline-flex items-center justify-center gap-2 rounded-md px-3.5 py-2.5 text-[11px] uppercase tracking-[0.12em] transition-all min-w-0',
                    'bg-[#c7a64a] text-black font-bold hover:bg-[#d4b55a]' => $n['state'] === 'serializing',
                    'border border-white/10 text-[#f2efe8] font-semibold hover:border-white/20 hover:bg-white/5' => $n['state'] !== 'serializing',
                ])>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    <span class="truncate">Continue Writing</span>
                </a>
            @endif
            <a href="{{ route('writer.novels.edit', $n['id']) }}" class="inline-flex items-center justify-center gap-2 rounded-md border border-white/10 px-3.5 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 016.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" /></svg>
                Details
            </a>
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="grid gap-4 md:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">

    <div class="rounded-md border border-white/5 bg-[#121212] p-5">
        <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">{{ strtoupper($novels->first()->title ?? 'Your Studio') }}</p>
        <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight mb-5">Your publishing desk</h3>

        <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_minmax(0,320px)]">
            <div>
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Next to Publish</p>
                @if($nextToPublish)
                    <div class="rounded-sm border border-white/5 bg-[#0a0a0a] p-4.5 mb-3.5">
                        <h4 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight mb-1.5">{{ $nextToPublish->title }}</h4>
                        <p class="text-[11.5px] text-[#a3a3a3]">{{ number_format($nextDraftWords) }} words · {{ $nextToPublish->status === 'draft' ? 'Draft' : 'Scheduled' }} · {{ $nextToPublish->novel->title ?? 'Untitled' }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2.5">
                        <a href="{{ route('writer.novels.chapters.edit', ['novel' => $nextToPublish->novel_id, 'chapter' => $nextToPublish->id]) }}" class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            Open Chapter
                        </a>
                        <a href="{{ route('writer.chapters', ['novel_id' => $nextToPublish->novel_id]) }}" class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Manage
                        </a>
                    </div>
                @else
                    <div class="rounded-sm border border-white/5 bg-[#0a0a0a] p-4.5 mb-3.5">
                        <p class="text-[12.5px] text-[#a3a3a3]">Nothing waiting to publish. Every chapter is live.</p>
                    </div>
                @endif
            </div>

            <div class="rounded-sm border border-white/5 bg-[#0a0a0a] p-4.5">
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3] mb-3">Library Summary</p>
                <div class="space-y-2.5 mb-3.5">
                    <p class="text-[12.5px] text-[#f2efe8]"><span class="text-[#c7a64a] font-bold">{{ $librarySummary['published_chapters'] }}</span> published chapters</p>
                    <p class="text-[12.5px] text-[#f2efe8]"><span class="text-[#c7a64a] font-bold">{{ $librarySummary['total_novels'] }}</span> novels · <span class="text-[#c7a64a] font-bold">{{ $librarySummary['total_words'] }}</span> words</p>
                </div>
                <p class="text-[10.5px] text-[#a3a3a3]">Private drafts are only visible to you.</p>
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
