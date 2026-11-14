@extends('layouts.writer', [
    'active' => $active ?? 'analytics',
])

@section('dashboard-content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        @foreach(['7' => '7 days', '30' => '30 days', '90' => '90 days', 'all' => 'All time'] as $key => $label)
            <a href="{{ route('writer.analytics', ['range' => $key]) }}"
               class="rounded-sm border {{ $range === $key ? 'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' : 'border-white/5 bg-[#121212] text-[#a3a3a3] hover:text-[#f2efe8] hover:border-white/10' }} px-3.5 py-1.5 text-[11px] font-semibold tracking-wide transition-all">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <div class="flex items-center gap-2.5">
        <div class="rounded-sm border border-white/10 bg-[#121212] px-3 py-1.5 text-[10.5px] font-semibold text-[#f2efe8] tabular-nums tracking-wide">
            {{ $rangeLabel }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 mb-6">
    @foreach($kpis as $k)
    <div class="rounded-md border border-white/5 bg-[#121212] p-4.5 hover:border-[#c7a64a]/20 transition-all">
        <p class="text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3] mb-2.5">{{ $k['label'] }}</p>
        <p class="text-[30px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight">{{ $k['value'] }}</p>
        @if(!empty($k['delta']))
            <p class="mt-2.5 text-[11px] font-semibold tracking-wide {{ ($k['delta_dir'] ?? 'up') === 'down' ? 'text-[#a3a3a3]' : 'text-[#c7a64a]' }}">
                @if(($k['delta_dir'] ?? 'up') === 'down')
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-3 w-3 -mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 112 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-3 w-3 -mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                @endif
                {{ $k['delta'] }}
            </p>
        @endif
        @if(!empty($k['sub']))
            <p class="mt-2.5 text-[11px] text-[#a3a3a3]">{{ $k['sub'] }}</p>
        @endif
    </div>
    @endforeach
</div>

<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
    <div class="space-y-6 min-w-0">

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Chapter Reads</p>
                    <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">A growing readership</h3>
                    <p class="mt-2 text-[11.5px] text-[#a3a3a3]">{{ $novels->first()->title ?? 'Your novels' }} · {{ number_format($viewsRange) }} reads in this period</p>
                </div>
                <div class="shrink-0 flex flex-col items-end gap-2">
                    <span class="inline-flex items-center gap-1.5 text-[10.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>
                        Reads
                    </span>
                </div>
            </div>

            @php
                $chart = $chartData ?? [];
                $labels = $chartLabels ?? [];
                $n = count($chart);
                $maxV = max(1, $n ? max($chart) : 1);
                $points = [];
                foreach ($chart as $i => $v) {
                    $x = $n > 1 ? round(40 + $i * (630 / ($n - 1)), 1) : 355;
                    $y = round(220 - ((int) $v / $maxV) * 180, 1);
                    $points[] = [$x, $y];
                }
                $polyStr = implode(' ', array_map(fn ($p) => $p[0] . ',' . $p[1], $points));
                $areaStr = $points ? $polyStr . ' ' . end($points)[0] . ',230 ' . $points[0][0] . ',230' : '';
                $fmt = fn ($v) => $v >= 1000 ? rtrim(rtrim(number_format($v / 1000, 1), '0'), '.') . 'K' : (string) $v;
                $gridYs = [40, 100, 160, 220];
                $labelStep = max(1, (int) ceil($n / 7));
            @endphp

            <div class="h-[260px] relative">
                <svg viewBox="0 0 680 240" class="w-full h-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="readsFill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#c7a64a" stop-opacity="0.28"/>
                            <stop offset="100%" stop-color="#c7a64a" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    @foreach($gridYs as $gy)
                        <line x1="30" y1="{{ $gy }}" x2="670" y2="{{ $gy }}" stroke="rgba(255,255,255,0.05)" stroke-width="1"/>
                    @endforeach
                    <g fill="#a3a3a3" font-size="9" font-weight="600" letter-spacing="1">
                        <text x="10" y="44" text-anchor="start">{{ $fmt($maxV) }}</text>
                        <text x="10" y="104" text-anchor="start">{{ $fmt((int) round($maxV * 2 / 3)) }}</text>
                        <text x="10" y="164" text-anchor="start">{{ $fmt((int) round($maxV / 3)) }}</text>
                        <text x="10" y="224" text-anchor="start">0</text>
                    </g>
                    @if($points)
                        <polygon fill="url(#readsFill)" points="{{ $areaStr }}"/>
                        <polyline fill="none" stroke="#c7a64a" stroke-width="2.5" points="{{ $polyStr }}"/>
                        @foreach($points as $pt)
                            <circle cx="{{ $pt[0] }}" cy="{{ $pt[1] }}" r="3.5" fill="#0a0a0a" stroke="#c7a64a" stroke-width="2"/>
                        @endforeach
                    @endif
                    <g fill="#a3a3a3" font-size="9" font-weight="600" letter-spacing="1" text-anchor="middle">
                        @foreach($labels as $i => $lab)
                            @if($i % $labelStep === 0)
                                <text x="{{ $points[$i][0] ?? 40 }}" y="252">{{ $lab }}</text>
                            @endif
                        @endforeach
                    </g>
                </svg>
            </div>
            @if($bestDay > 0)
                <p class="mt-2 text-[11.5px] text-[#a3a3a3]">{{ $fmt($bestDay) }} reads in a single day was your strongest point of this period.</p>
            @endif
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Published Chapters</p>
                    <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">Chapter performance</h3>
                    <p class="mt-2 text-[11.5px] text-[#a3a3a3]">Top performing chapters by reads</p>
                </div>
                <a href="{{ route('writer.chapters') }}" class="text-[10.5px] font-semibold uppercase tracking-[0.18em] text-[#c7a64a] hover:text-[#d4b55a] inline-flex items-center gap-1.5 shrink-0 mt-1">
                    All {{ $publishedTotal }} Chapters
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            @if($chapterTable->isEmpty())
                <p class="py-8 text-center text-[12.5px] text-[#a3a3a3]">No published chapters yet. Publish a chapter to see its performance.</p>
            @else
            @php $topChapters = $chapterTable->take(8); @endphp
            <div class="overflow-hidden rounded-md border border-white/5">
                <div class="grid grid-cols-12 bg-[#0a0a0a] px-4 py-3 text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">
                    <div class="col-span-6">Chapter</div>
                    <div class="col-span-2 text-right">Readers</div>
                    <div class="col-span-2 text-right">Words</div>
                    <div class="col-span-2 text-right">Comments</div>
                </div>
                @foreach($topChapters as $row)
                <div class="grid grid-cols-12 items-center px-4 py-3.5 border-t border-white/5 hover:bg-white/[0.015] transition-colors">
                    <div class="col-span-6 text-[13px] text-[#f2efe8] font-medium truncate">
                        <span class="text-[#a3a3a3] mr-1.5 font-semibold">{{ $row['num'] }} ·</span>{{ $row['title'] }}
                    </div>
                    <div class="col-span-2 text-right text-[12px] text-[#f2efe8] tabular-nums font-semibold">{{ number_format($row['reads']) }}</div>
                    <div class="col-span-2 text-right text-[12px] text-[#f2efe8] tabular-nums">{{ number_format($row['words']) }}</div>
                    <div class="col-span-2 text-right text-[12px] text-[#f2efe8] tabular-nums">{{ number_format($row['comments']) }}</div>
                </div>
                @endforeach
            </div>
            @if($publishedTotal > $topChapters->count())
                <div class="mt-3.5 flex items-center justify-between">
                    <p class="text-[11px] text-[#a3a3a3]">Showing top {{ $topChapters->count() }} of {{ $publishedTotal }} published chapters</p>
                    <a href="{{ route('writer.chapters') }}" class="text-[10.5px] font-bold uppercase tracking-[0.18em] text-[#c7a64a] hover:text-[#d4b55a] inline-flex items-center gap-1.5">
                        View all
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            @endif
            @endif
        </div>

    </div>

    <aside class="space-y-6 min-w-0">

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-2">Reach</p>
            <h3 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight mb-1">Reads by novel</h3>
            <p class="mb-4 text-[11.5px] text-[#a3a3a3]">Share of total reads</p>

            @if($readsByNovel->isEmpty())
                <p class="text-[12.5px] text-[#a3a3a3]">No data yet.</p>
            @else
            <div class="space-y-4">
                @foreach($readsByNovel as $d)
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-[12.5px] text-[#f2efe8] truncate pr-2">{{ $d['name'] }}</p>
                        <p class="text-[11.5px] font-bold text-[#c7a64a] tabular-nums shrink-0">{{ $d['pct'] }}%</p>
                    </div>
                    <div class="h-[4px] w-full rounded-full bg-white/5 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#c7a64a] to-[#d4b55a]" style="width: {{ $d['pct'] }}%"></div>
                    </div>
                    <p class="mt-1.5 text-[10.5px] text-[#a3a3a3]">{{ number_format($d['readers']) }} reads</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-2">Reader Engagement</p>
            <h3 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight mb-1">The story holds them</h3>
            <p class="text-[32px] font-serif font-medium text-[#f2efe8] leading-none mb-1.5 tracking-tight">{{ $avgSessionLabel }}</p>
            <p class="mb-6 text-[10.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Average Reading Session</p>

            <div class="space-y-3.5 border-t border-white/5 pt-4">
                <div class="flex items-center justify-between">
                    <p class="text-[12px] text-[#a3a3a3]">Added to reading shelves</p>
                    <p class="text-[12px] font-bold text-[#c7a64a] tabular-nums">{{ number_format($shelfAdds) }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-[12px] text-[#a3a3a3]">Author followers</p>
                    <p class="text-[12px] font-bold text-[#c7a64a] tabular-nums">{{ number_format($followers) }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-[12px] text-[#a3a3a3]">Total comments</p>
                    <p class="text-[12px] font-bold text-[#c7a64a] tabular-nums">{{ number_format($stats['totalComments']) }}</p>
                </div>
            </div>

            @if($topChapter)
            <div class="mt-5">
                <span class="inline-flex rounded-sm border border-[#c7a64a]/30 px-2.5 py-1 text-[9px] font-bold uppercase tracking-[0.18em] text-[#c7a64a]">
                    Chapter {{ $topChapter['num'] }} · Most Read
                </span>
            </div>
            @endif
        </div>

    </aside>
</div>

@endsection
