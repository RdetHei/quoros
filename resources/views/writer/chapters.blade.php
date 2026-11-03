@extends('layouts.writer', [
    'active' => $active ?? 'chapters',
])

@section('dashboard-content')

@if(!$selectedNovel)
    <div class="rounded-md border border-white/5 bg-[#121212] px-6 py-16 text-center">
        <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight mb-2">No manuscript yet.</h3>
        <p class="mx-auto mb-6 max-w-md text-[12.5px] leading-relaxed text-[#a3a3a3]">Create your first novel to start writing chapters.</p>
        <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[#c7a64a] px-4 py-2.5 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
            Create a Novel
        </a>
    </div>
@else

@php
    $chapters = $chaptersList;
    $activeIndex = 0;
    foreach ($chapters as $i => $c) { if ($c['active']) { $activeIndex = $i; break; } }
    $chapterLabel = str_pad((string) ($activeIndex + 1), 2, '0', STR_PAD_LEFT);

    $paragraphs = [];
    if ($activeChapter && $activeChapter->content) {
        $normalized = trim(str_replace(["\r\n", "\r"], "\n", $activeChapter->content));
        $paragraphs = preg_split("/\n\s*\n/", $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    }

    $chart = $chartData ?? [];
    $chartMax = max(1, count($chart) ? max($chart) : 1);
    $chartCount = count($chart);
    $points = [];
    foreach ($chart as $i => $v) {
        $x = $chartCount > 1 ? round($i * (275 / ($chartCount - 1)) + 10, 1) : 150;
        $y = round(72 - ((int) $v / $chartMax) * 54, 1);
        $points[] = [$x, $y];
    }
    $polyStr = implode(' ', array_map(fn ($p) => $p[0] . ',' . $p[1], $points));
    $areaStr = $points ? $polyStr . ' ' . end($points)[0] . ',72 ' . $points[0][0] . ',72' : '';
@endphp

@if($novels->count() > 1)
<div class="mb-6 flex flex-wrap items-center gap-2">
    @foreach($novels as $nv)
        <a href="{{ route('writer.chapters', ['novel_id' => $nv->id]) }}"
           class="inline-flex items-center gap-1.5 rounded-sm border {{ $selectedNovel->id === $nv->id ? 'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' : 'border-white/5 bg-[#121212] text-[#a3a3a3] hover:text-[#f2efe8] hover:border-white/10' }} px-3 py-1.5 text-[11px] font-semibold tracking-wide transition-all">
            {{ $nv->title }}
        </a>
    @endforeach
</div>
@endif

<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
    <div class="min-w-0">

        <div class="rounded-md border border-white/5 bg-[#121212] overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-sm border border-[#c7a64a]/30 bg-[#c7a64a]/10 text-[#c7a64a]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </span>
                    <div>
                        <h2 class="text-[15px] font-medium text-[#f2efe8]">Manuscript Editor</h2>
                        <p class="text-[10px] uppercase tracking-[0.22em] text-[#a3a3a3] font-semibold mt-0.5">{{ $selectedNovel->title }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('writer.novels.chapters.bulk-create', $selectedNovel->id) }}" title="Bulk import" class="p-2 rounded-sm border border-white/5 hover:border-white/15 hover:bg-white/5 text-[#a3a3a3] hover:text-[#f2efe8] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                    </a>
                    <a href="{{ route('writer.novels.chapters.create', $selectedNovel->id) }}" title="New chapter" class="p-2 rounded-sm border border-white/5 hover:border-white/15 hover:bg-white/5 text-[#a3a3a3] hover:text-[#f2efe8] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                    </a>
                </div>
            </div>

            @if($activeChapter)
            <div class="flex items-center justify-between px-5 py-3 border-b border-white/5 bg-[#0a0a0a]">
                <span class="rounded-sm border border-white/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#f2efe8]">
                    Chapter {{ $chapterLabel }} of {{ $chapters->count() }}
                </span>
                <div class="flex items-center gap-5">
                    <span @class([
                        'rounded-sm border px-2 py-0.5 text-[9px] font-bold uppercase tracking-[0.15em]',
                        'border-emerald-500/30 text-emerald-400' => $activeChapter->status === 'published',
                        'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' => $activeChapter->status !== 'published',
                    ])>{{ ucfirst($activeChapter->status) }}</span>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-[#f2efe8] tabular-nums">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        {{ number_format($activeChapter->word_count) }} words
                    </span>
                </div>
            </div>

            <div class="px-6 sm:px-12 md:px-20 py-10 md:py-14 max-w-full">
                <div class="mx-auto max-w-[65ch]">
                    <p class="text-center text-[10px] font-bold uppercase tracking-[0.3em] text-[#c7a64a] mb-4">Chapter {{ $chapterLabel }}</p>
                    <h1 class="font-serif text-[34px] md:text-[38px] text-center text-[#f2efe8] tracking-tight mb-4">
                        {{ $activeChapter->title }}
                    </h1>
                    <div class="mx-auto w-10 h-px bg-[#c7a64a]/60 mb-10"></div>

                    @if(count($paragraphs))
                        <div class="space-y-6 text-[15.5px] md:text-[16px] leading-[1.95] text-[#e5dfd0] font-serif">
                            @foreach($paragraphs as $i => $p)
                                <p class="@if($i === 0) first-letter:font-serif first-letter:text-[56px] first-letter:leading-none first-letter:mr-2 first-letter:mt-1.5 first-letter:float-left first-letter:text-[#c7a64a] first-letter:font-medium @endif">{!! nl2br(e(trim($p))) !!}</p>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-[13px] text-[#a3a3a3] italic">This chapter has no content yet. Open the editor to begin writing.</p>
                    @endif

                    <div class="mt-12 flex items-center justify-center gap-3">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>
                        <div class="h-px w-24 bg-gradient-to-r from-transparent via-[#c7a64a]/30 to-transparent"></div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/5 bg-[#0a0a0a] px-5 py-4 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap gap-2.5">
                    <a href="{{ route('writer.novels.chapters.edit', ['novel' => $selectedNovel->id, 'chapter' => $activeChapter->id]) }}" class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        Edit Chapter
                    </a>
                    <a href="{{ route('chapters.show', [$selectedNovel->slug, $activeChapter->route_identifier]) }}" target="_blank" class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        Preview
                    </a>
                </div>
                <div class="flex flex-wrap gap-2.5">
                    @if($activeChapter->status !== 'published')
                        <a href="{{ route('writer.novels.chapters.edit', ['novel' => $selectedNovel->id, 'chapter' => $activeChapter->id]) }}" class="inline-flex items-center gap-2 rounded-md bg-[#c7a64a] px-5 py-2.5 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">
                            Publish
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('writer.novels.chapters.destroy', ['novel' => $selectedNovel->id, 'chapter' => $activeChapter->id]) }}" onsubmit="return confirm('Delete “{{ $activeChapter->title }}”? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-red-400/90 transition-all hover:border-red-500/30 hover:bg-red-500/10">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="px-6 py-20 text-center">
                <p class="text-[13px] text-[#a3a3a3] mb-5">No chapters yet in this novel.</p>
                <a href="{{ route('writer.novels.chapters.create', $selectedNovel->id) }}" class="inline-flex items-center gap-2 rounded-md bg-[#c7a64a] px-4 py-2.5 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                    Write First Chapter
                </a>
            </div>
            @endif
        </div>

    </div>

    <aside class="space-y-5 min-w-0">

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <h3 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight">Novel Hub</h3>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full {{ $selectedNovel->status === 'ongoing' ? 'bg-amber-400 animate-pulse' : 'bg-emerald-400' }}"></span>
                        <span class="text-[9.5px] font-bold uppercase tracking-[0.2em] {{ $selectedNovel->status === 'ongoing' ? 'text-amber-400' : 'text-emerald-400' }}">{{ ucfirst($selectedNovel->status) }}</span>
                    </span>
                </div>
            </div>

            <div class="mb-5">
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Platform Activity</p>
                <div class="flex items-start justify-between mb-1">
                    <h4 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight">Performance</h4>
                </div>
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3] mb-3">Reader Activity · Last 7 Days</p>

                <div class="flex items-baseline gap-3 mb-4">
                    <p class="text-[32px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight">{{ number_format(array_sum($chartData ?? [])) }}</p>
                    <span class="text-[11px] text-[#a3a3a3] mb-0.5">reads</span>
                    <div class="ml-auto flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#c7a64a]" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <span class="text-[12.5px] font-bold text-[#f2efe8] tabular-nums">{{ number_format($hub['rating'], 1) }}</span>
                        <span class="text-[10.5px] text-[#a3a3a3]">[{{ $hub['rating_count'] }}]</span>
                    </div>
                </div>

                <svg viewBox="0 0 300 80" class="w-full h-[72px]" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="perfFill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#c7a64a" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#c7a64a" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    @if($areaStr)
                        <polygon fill="url(#perfFill)" points="{{ $areaStr }}"/>
                        <polyline fill="none" stroke="#c7a64a" stroke-width="2.2" points="{{ $polyStr }}"/>
                        @foreach($points as $pt)
                            <circle cx="{{ $pt[0] }}" cy="{{ $pt[1] }}" r="2.5" fill="#c7a64a"/>
                        @endforeach
                    @else
                        <line x1="10" y1="72" x2="285" y2="72" stroke="#c7a64a" stroke-width="2.2"/>
                    @endif
                </svg>
                <div class="mt-1 grid grid-cols-7 text-center text-[8.5px] uppercase tracking-[0.22em] text-[#a3a3a3] font-semibold">
                    @foreach(($chartLabels ?? ['M','T','W','T','F','S','S']) as $d) <span>{{ $d }}</span> @endforeach
                </div>
            </div>
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Writing</p>
                    <h3 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight">Chapters</h3>
                    <p class="mt-1 text-[11px] text-[#a3a3a3]">
                        <span class="font-bold text-[#f2efe8] tabular-nums">{{ $hub['chapters'] }}</span> chapters · <span class="font-bold text-[#f2efe8] tabular-nums">{{ number_format($hub['words']) }}</span> words
                    </p>
                </div>
                <a href="{{ route('writer.novels.chapters.create', $selectedNovel->id) }}" class="flex h-7 w-7 shrink-0 items-center justify-center rounded-sm border border-white/10 text-[#f2efe8] hover:border-[#c7a64a]/40 hover:bg-[#c7a64a]/10 hover:text-[#c7a64a] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                </a>
            </div>

            <div class="space-y-1.5 mb-4 max-h-[340px] overflow-y-auto custom-scrollbar pr-1">
                @forelse($chapters as $ch)
                <a href="{{ route('writer.chapters', ['novel_id' => $selectedNovel->id, 'chapter_id' => $ch['id']]) }}" class="flex items-start gap-2.5 rounded-md border {{ $ch['active'] ? 'border-[#c7a64a]/30 bg-[#c7a64a]/8' : 'border-white/5 bg-transparent hover:border-white/10 hover:bg-white/[0.02]' }} p-3 transition-all group">
                    <div class="flex shrink-0 h-7 w-7 items-center justify-center rounded-sm border {{ $ch['active'] ? 'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' : 'border-white/10 text-[#a3a3a3]' }} text-[11px] font-bold tabular-nums">
                        {{ $ch['num'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[12.5px] font-medium text-[#f2efe8] truncate">{{ $ch['title'] }}</p>
                        <p class="mt-0.5 text-[10px] text-[#a3a3a3] tabular-nums">{{ $ch['words'] }}</p>
                    </div>
                    <span class="shrink-0 mt-0.5 rounded-sm border {{ $ch['status_class'] === 'published' ? 'border-emerald-500/30 text-emerald-400' : 'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' }} px-2 py-0.5 text-[8.5px] font-bold uppercase tracking-[0.15em]">
                        {{ $ch['status'] }}
                    </span>
                </a>
                @empty
                <p class="px-1 py-6 text-center text-[12px] text-[#a3a3a3]">No chapters yet.</p>
                @endforelse
            </div>

            <a href="{{ route('writer.novels.workspace', $selectedNovel->id) }}" class="flex items-center justify-center gap-1.5 py-2 text-[10.5px] font-bold uppercase tracking-[0.2em] text-[#c7a64a] hover:text-[#d4b55a] transition-colors">
                Manage All Chapters
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5 relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-40 h-40 rounded-full bg-[#c7a64a]/8 blur-3xl pointer-events-none"></div>
            <div class="relative">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Community</p>
                        <h3 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight">Character Codex</h3>
                        <p class="mt-1 text-[10.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">
                            Cast · {{ $cast->count() }} {{ \Illuminate\Support\Str::plural('Profile', $cast->count()) }}
                        </p>
                    </div>
                    <a href="{{ route('writer.codex', ['novel_id' => $selectedNovel->id]) }}" class="flex h-7 w-7 shrink-0 items-center justify-center rounded-sm border border-white/10 text-[#f2efe8] hover:border-[#c7a64a]/40 hover:bg-[#c7a64a]/10 hover:text-[#c7a64a] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>

                @if($cast->isNotEmpty())
                    <div class="flex gap-3 flex-wrap -space-x-1 mb-4">
                        @foreach($cast->take(5) as $character)
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="h-11 w-11 rounded-full border-2 border-[#121212] bg-gradient-to-br from-amber-800/60 to-stone-700 flex items-center justify-center text-[11px] font-bold text-[#f2efe8] shadow-lg overflow-hidden">
                                    @if($character->image_url)
                                        <img src="{{ $character->image_url }}" alt="{{ $character->name }}" class="h-full w-full object-cover">
                                    @else
                                        {{ strtoupper(substr($character->name, 0, 1)) }}
                                    @endif
                                </div>
                                <p class="text-[10.5px] font-semibold text-[#f2efe8]">{{ $character->name }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mb-4 text-[12px] text-[#a3a3a3]">No characters yet for this novel.</p>
                @endif

                <div class="flex items-center justify-between mt-3 pt-3 border-t border-white/5">
                    <p class="inline-flex items-center gap-1.5 text-[10.5px] text-[#a3a3a3]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                        <span>{{ $selectedNovel->characterRelationships()->count() }} relationships</span>
                    </p>
                    <a href="{{ route('writer.codex', ['novel_id' => $selectedNovel->id]) }}" class="text-[10.5px] font-bold uppercase tracking-[0.2em] text-[#c7a64a] hover:text-[#d4b55a] inline-flex items-center gap-1.5">
                        Open Codex
                    </a>
                </div>
            </div>
        </div>

    </aside>
</div>

@endif

@endsection
