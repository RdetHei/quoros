@extends('layouts.writer', [
    'active' => $active ?? 'dashboard',
])

@section('dashboard-content')

@php
    $heroNovel = $novels->first();
    $heroChapter = $heroNovel ? $heroNovel->chapters->sortByDesc('updated_at')->first() : null;
    $heroGenres = $heroNovel ? $heroNovel->genres->pluck('name')->take(2)->implode(' · ') : '';
    $heroTotal = $heroNovel ? $heroNovel->chapters_count : 0;
    $heroPublished = $heroNovel ? $heroNovel->published_chapters_count : 0;
    $heroPct = $heroTotal > 0 ? (int) round($heroPublished / $heroTotal * 100) : 0;

    $chart = $chartData ?? [];
    $chartLabels = $chartLabels ?? [];
    $chartMax = max(1, count($chart) ? max($chart) : 1);
    $chartCount = count($chart);
    $points = [];
    foreach ($chart as $i => $v) {
        $x = $chartCount > 1 ? round(10 + $i * (288 / ($chartCount - 1)), 1) : 150;
        $y = round(100 - ((int) $v / $chartMax) * 72, 1);
        $points[] = [$x, $y];
    }
    $polyStr = implode(' ', array_map(fn ($p) => $p[0] . ',' . $p[1], $points));
    $areaStr = $points ? $polyStr . ' ' . end($points)[0] . ',112 ' . $points[0][0] . ',112' : '';
    $chartTotal = array_sum($chart);
@endphp

<div class="mb-5 flex items-center justify-between">
    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Studio Overview · Last 7 Days</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 mb-6">
    @foreach($statsCards as $card)
    <div class="rounded-md border border-white/5 bg-[#121212] p-4.5 transition-all hover:border-[#c7a64a]/20">
        <p class="text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3] mb-2.5">{{ $card['label'] }}</p>
        <p class="text-[30px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight">{{ $card['value'] }}</p>
        @if(!empty($card['sub']))
            <p class="mt-2.5 text-[11px] text-[#a3a3a3]">{{ $card['sub'] }}</p>
        @endif
        @if(!empty($card['delta']))
            <p class="mt-2.5 text-[11px] font-semibold tracking-wide {{ ($card['delta_dir'] ?? 'up') === 'down' ? 'text-[#a3a3a3]' : 'text-[#c7a64a]' }}">
                @if(($card['delta_dir'] ?? 'up') === 'down')
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-3 w-3 -mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 112 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-3 w-3 -mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                @endif
                {{ $card['delta'] }}
            </p>
        @endif
    </div>
    @endforeach
</div>

@if(!$heroNovel)
    <div class="rounded-md border border-white/5 bg-[#121212] px-6 py-16 text-center">
        <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight mb-2">Your studio is waiting.</h3>
        <p class="mx-auto mb-6 max-w-md text-[12.5px] leading-relaxed text-[#a3a3a3]">Create your first novel to begin tracking chapters, readers, and reviews.</p>
        <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[#c7a64a] px-4 py-2.5 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
            Create a Novel
        </a>
    </div>
@else
<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
    <div class="space-y-6 min-w-0">

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Your Current Novel</p>
                    <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">
                        {{ $heroPct >= 100 ? 'A complete story, for now' : 'A story taking shape' }}
                    </h3>
                </div>
                <span class="shrink-0 mt-1 rounded border border-[#c7a64a]/30 px-2.5 py-1 text-[9.5px] font-semibold uppercase tracking-[0.15em] text-[#c7a64a]">
                    {{ $heroPublished === 0 ? 'Draft' : ($heroNovel->status === 'complete' ? 'Complete' : 'In Progress') }}
                </span>
            </div>

            <div class="flex gap-5 items-start">
                <div class="w-[128px] shrink-0 aspect-[3/4] rounded overflow-hidden border border-white/5 bg-gradient-to-br from-amber-950/60 via-stone-900 to-black relative">
                    @if($heroNovel->cover_url)
                        <img src="{{ $heroNovel->cover_url }}" alt="{{ $heroNovel->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-center px-3">
                            <div>
                                <p class="text-[9px] uppercase tracking-[0.2em] text-[#c7a64a]/80 mb-2 font-semibold">{{ auth()->user()->name }}</p>
                                <p class="font-serif text-[18px] text-[#f2efe8] leading-snug">{{ $heroNovel->title }}</p>
                                <div class="mt-5 mx-auto w-8 border-t border-[#c7a64a]/40"></div>
                                <p class="mt-4 text-[8.5px] uppercase tracking-[0.2em] text-[#a3a3a3] font-medium">A Novel</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">{{ $heroNovel->title }}</h4>
                    <p class="mt-1 text-[11.5px] text-[#a3a3a3]">
                        {{ $heroGenres ?: 'Uncategorised' }}
                    </p>

                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <p class="text-[10.5px] text-[#a3a3a3]">{{ $heroPublished }} of {{ $heroTotal }} CHAPTERS PUBLISHED</p>
                            <p class="text-[10.5px] font-semibold text-[#c7a64a]">{{ $heroPct }}%</p>
                        </div>
                        <div class="h-[3px] w-full rounded-full bg-white/5 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-[#c7a64a] to-[#d4b55a]" style="width: {{ $heroPct }}%"></div>
                        </div>
                    </div>

                    @if($heroChapter)
                        <p class="mt-4 text-[13px] text-[#f2efe8]">
                            Continue <span class="font-serif italic">{{ $heroChapter->title }}</span>
                            @if(! $heroChapter->isPubliclyPublished())
                                <span class="text-[#a3a3a3] text-[11px]">({{ strtolower($heroChapter->status_label) }})</span>
                            @endif
                        </p>
                    @else
                        <p class="mt-4 text-[13px] text-[#a3a3a3]">No chapters yet — begin the first one.</p>
                    @endif
                    <div class="mt-3.5 flex flex-wrap gap-2.5">
                        <a href="{{ route('writer.chapters', ['novel_id' => $heroNovel->id]) }}" class="inline-flex items-center gap-2 rounded-md bg-[#c7a64a] px-4 py-2.5 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            Continue Writing
                        </a>
                        <a href="{{ route('novels.show', $heroNovel->slug) }}" target="_blank" class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            Preview
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Return to the Page</p>
                    <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">Recent work</h3>
                </div>
                <a href="{{ route('writer.chapters') }}" class="text-[11px] font-semibold uppercase tracking-[0.15em] text-[#c7a64a] hover:text-[#d4b55a] inline-flex items-center gap-1.5">
                    All Chapters
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            @if($recentChapters->isEmpty())
                <p class="py-6 text-center text-[12.5px] text-[#a3a3a3]">No chapters yet. Your first page is waiting to be written.</p>
            @else
            <div class="space-y-2">
                @foreach($recentChapters as $ch)
                @php
                    $isPublished = $ch->isPubliclyPublished();
                    $num = $ch->order > 0 ? $ch->order : $ch->id;
                @endphp
                <a href="{{ route('writer.chapters', ['novel_id' => $ch->novel_id, 'chapter_id' => $ch->id]) }}" class="flex items-center gap-3 rounded-md border border-white/5 bg-[#0a0a0a] p-3.5 hover:border-white/10 transition-all">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-sm border border-white/10 bg-[#121212] text-[11px] font-bold text-[#c7a64a] tabular-nums">{{ str_pad((string) $num, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[13px] font-medium text-[#f2efe8] truncate">{{ $ch->title }}</p>
                        <p class="mt-0.5 text-[10.5px] text-[#a3a3a3]">{{ number_format($ch->word_count) }} words · Edited {{ $ch->updated_at->diffForHumans() }}</p>
                    </div>
                    <span class="shrink-0 rounded border {{ $isPublished ? 'border-[#c7a64a]/30 text-[#c7a64a]' : 'border-white/10 text-[#a3a3a3]' }} px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[0.15em]">{{ $ch->status_label }}</span>
                </a>
                @endforeach
            </div>
            @endif
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Community</p>
                    <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">From your readers</h3>
                </div>
                <a href="{{ route('writer.comments') }}" class="text-[11px] font-semibold uppercase tracking-[0.15em] text-[#c7a64a] hover:text-[#d4b55a] inline-flex items-center gap-1.5">
                    Comments
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
            @if($comments->isEmpty() && $reviews->isEmpty())
                <p class="py-6 text-center text-[12.5px] text-[#a3a3a3]">No reader messages yet. Publish a chapter to start the conversation.</p>
            @else
            <div class="space-y-4">
                @foreach($comments->take(2) as $c)
                <div class="flex gap-3 items-start">
                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-[#c7a64a]"></span>
                    <div class="min-w-0">
                        <p class="text-[13px] text-[#f2efe8]"><span class="font-semibold">{{ $c->user->name ?? 'A reader' }}</span> · <span class="text-[#a3a3a3]">“{{ \Illuminate\Support\Str::limit($c->content, 90) }}”</span></p>
                        <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-[#a3a3a3]">{{ $c->chapter->title ?? 'Chapter' }} · {{ $c->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
                @foreach($reviews->take($comments->take(2)->count() > 0 ? 1 : 2) as $r)
                <div class="flex gap-3 items-start">
                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-[#c7a64a]"></span>
                    <div class="min-w-0">
                        <p class="text-[13px] text-[#f2efe8]"><span class="font-semibold">{{ $r->user->name ?? 'A reader' }}</span> rated <span class="text-[#c7a64a]">{{ $r->rating }} ★</span> <span class="text-[#a3a3a3]">{{ $r->novel->title ?? '' }}</span></p>
                        <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-[#a3a3a3]">{{ $r->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    <aside class="space-y-6 min-w-0">

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Last 7 Days</p>
                    <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">Readers are returning</h3>
                </div>
            </div>
            <div class="flex items-baseline justify-between mb-1">
                <p class="text-[28px] font-serif font-medium text-[#f2efe8] tracking-tight">{{ number_format($chartTotal) }} reads</p>
                @php
                    $weekDelta = $stats['viewsPrev7'] > 0
                        ? round((($stats['viewsLast7'] - $stats['viewsPrev7']) / $stats['viewsPrev7']) * 100, 1)
                        : ($stats['viewsLast7'] > 0 ? 100.0 : 0.0);
                @endphp
                <span class="inline-flex items-center gap-1 rounded border px-2 py-1 text-[9.5px] font-bold {{ $weekDelta >= 0 ? 'border-[#c7a64a]/30 text-[#c7a64a]' : 'border-white/10 text-[#a3a3a3]' }}">
                    @if($weekDelta >= 0)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 112 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    @endif
                    {{ abs($weekDelta) }}%
                </span>
            </div>

            <div class="mt-4">
                <svg viewBox="0 0 320 120" class="w-full h-[110px]" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="readersFill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#c7a64a" stop-opacity="0.32"/>
                            <stop offset="100%" stop-color="#c7a64a" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    @if($points)
                        <polygon fill="url(#readersFill)" points="{{ $areaStr }}"/>
                        <polyline fill="none" stroke="#c7a64a" stroke-width="2" points="{{ $polyStr }}"/>
                        @foreach($points as $p)
                            <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="3" fill="#c7a64a"/>
                        @endforeach
                    @endif
                </svg>
                <div class="mt-1 grid grid-cols-7 text-center text-[9px] uppercase tracking-[0.2em] text-[#a3a3a3] font-medium">
                    @foreach($chartLabels as $d)
                        <span>{{ $d }}</span>
                    @endforeach
                </div>
            </div>
            <p class="mt-3 text-[11px] text-[#a3a3a3]">{{ $stats['viewsToday'] }} reads today. <a href="{{ route('writer.analytics') }}" class="text-[#c7a64a] font-semibold">View analytics →</a></p>
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="mb-4">
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Your Writing Desk</p>
                <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">Make room for the next page</h3>
            </div>

            <div class="flex items-baseline justify-between mb-1.5">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">Today's Writing Goal</p>
                </div>
                <span class="rounded border border-[#c7a64a]/30 px-2 py-1 text-[9.5px] font-bold text-[#c7a64a]">{{ $dailyGoal['percent'] }}%</span>
            </div>
            <p class="text-[30px] font-serif font-medium text-[#f2efe8] leading-none mb-3 tracking-tight">
                {{ number_format($dailyGoal['current']) }} <span class="text-[#a3a3a3] text-[18px]">/ {{ number_format($dailyGoal['target']) }} words</span>
            </p>
            <div class="h-[4px] w-full rounded-full bg-white/5 overflow-hidden mb-3">
                <div class="h-full rounded-full bg-gradient-to-r from-[#c7a64a] to-[#d4b55a]" style="width: {{ $dailyGoal['percent'] }}%"></div>
            </div>
            <p class="text-[11.5px] text-[#a3a3a3] mb-5">
                @if($dailyGoal['remaining'] > 0)
                    {{ number_format($dailyGoal['remaining']) }} words to go. A little more quiet, a little more story.
                @else
                    Goal reached. Every word today was a page well spent.
                @endif
            </p>

            <div class="border-t border-white/5 pt-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3] mb-3">Next Tasks</p>
                @if(empty($nextTasks))
                    <p class="text-[12.5px] text-[#a3a3a3]">You're all caught up. Time to write something new.</p>
                @else
                <div class="space-y-3">
                    @foreach($nextTasks as $task)
                    <label class="flex items-start gap-3 group cursor-pointer">
                        <input type="checkbox" class="mt-0.5 h-3.5 w-3.5 rounded-sm border border-white/15 bg-[#0a0a0a] text-[#c7a64a] focus:ring-[#c7a64a]/50 shrink-0">
                        <div class="min-w-0">
                            <p class="text-[12.5px] font-medium text-[#f2efe8] group-hover:text-[#c7a64a] transition-colors">{{ $task['title'] }}</p>
                            <p class="mt-0.5 text-[10.5px] text-[#a3a3a3]">{{ $task['note'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @endif
            </div>

            <p class="mt-5 border-t border-white/5 pt-4 italic font-serif text-[12.5px] text-[#a3a3a3]/80 text-center">
                “The next sentence is always a beginning.”
            </p>
        </div>

    </aside>
</div>
@endif

@endsection
