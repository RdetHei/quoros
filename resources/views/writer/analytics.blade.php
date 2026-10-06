@extends('layouts.writer', [
    'active' => $active ?? 'analytics',
])

@section('dashboard-content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        @php
            $tabs = ['7 days' => true, '30 days' => false, '90 days' => false, 'All time' => false];
        @endphp
        @foreach($tabs as $label => $act)
            <button class="rounded-sm border {{ $act ? 'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' : 'border-white/5 bg-[#121212] text-[#a3a3a3] hover:text-[#f2efe8] hover:border-white/10' }} px-3.5 py-1.5 text-[11px] font-semibold tracking-wide transition-all">
                {{ $label }}
            </button>
        @endforeach
    </div>
    <div class="flex items-center gap-2.5">
        <div class="rounded-sm border border-white/10 bg-[#121212] px-3 py-1.5 text-[10.5px] font-semibold text-[#f2efe8] tabular-nums tracking-wide">
            Sep 27 – Oct 3, 2026
        </div>
        <button class="inline-flex items-center gap-2 rounded-md border border-white/10 bg-[#121212] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.15em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
            Export Report
        </button>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 mb-6">
    @foreach($kpis as $k)
    <div class="rounded-md border border-white/5 bg-[#121212] p-4.5 hover:border-[#c7a64a]/20 transition-all">
        <p class="text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3] mb-2.5">{{ $k['label'] }}</p>
        <p class="text-[30px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight">{{ $k['value'] }}</p>
        @if(!empty($k['delta']))
            <p class="mt-2.5 text-[11px] font-semibold text-[#c7a64a] tracking-wide">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline h-3 w-3 -mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
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
                    <p class="mt-2 text-[11.5px] text-[#a3a3a3]">{{ $currentNovel = ($novels->first()->title ?? 'The Glass Orchard') }} · 12,800 reads this week</p>
                </div>
                <div class="shrink-0 flex flex-col items-end gap-2">
                    <a href="#" class="text-[10.5px] font-semibold uppercase tracking-[0.18em] text-[#c7a64a] hover:text-[#d4b55a] inline-flex items-center gap-1.5">
                        Daily
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    <span class="inline-flex items-center gap-1.5 text-[10.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>
                        Reads
                    </span>
                </div>
            </div>

            <div class="h-[260px] relative">
                <svg viewBox="0 0 680 240" class="w-full h-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="readsFill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#c7a64a" stop-opacity="0.28"/>
                            <stop offset="100%" stop-color="#c7a64a" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    @foreach([40,100,160,220] as $y)
                        <line x1="30" y1="{{$y}}" x2="670" y2="{{$y}}" stroke="rgba(255,255,255,0.05)" stroke-width="1"/>
                    @endforeach
                    <g fill="#a3a3a3" font-size="9" font-weight="600" letter-spacing="1">
                        <text x="10" y="44" text-anchor="start">3K</text>
                        <text x="10" y="104" text-anchor="start">2K</text>
                        <text x="10" y="164" text-anchor="start">1K</text>
                        <text x="10" y="224" text-anchor="start">0</text>
                    </g>
                    <polygon fill="url(#readsFill)"
                      points="40,160 147,140 254,128 361,120 468,104 575,92 670,66 670,230 40,230"/>
                    <polyline fill="none" stroke="#c7a64a" stroke-width="2.5"
                      points="40,160 147,140 254,128 361,120 468,104 575,92 670,66"/>
                    @foreach([[40,160],[147,140],[254,128],[361,120],[468,104],[575,92],[670,66]] as $pt)
                        <circle cx="{{$pt[0]}}" cy="{{$pt[1]}}" r="3.5" fill="#0a0a0a" stroke="#c7a64a" stroke-width="2"/>
                    @endforeach
                    <g fill="#a3a3a3" font-size="9" font-weight="600" letter-spacing="1" text-anchor="middle">
                        <text x="40" y="252">Sep 27</text>
                        <text x="147" y="252">Sep 28</text>
                        <text x="254" y="252">Sep 29</text>
                        <text x="361" y="252">Sep 30</text>
                        <text x="468" y="252">Oct 1</text>
                        <text x="575" y="252">Oct 2</text>
                        <text x="670" y="252">Oct 3</text>
                    </g>
                </svg>
            </div>
            <p class="mt-2 text-[11.5px] text-[#a3a3a3]">October 3 brought 2,800 reads — your strongest day of the week.</p>
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Published Chapters</p>
                    <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">Chapter performance</h3>
                </div>
                <a href="#" class="text-[10.5px] font-semibold uppercase tracking-[0.18em] text-[#c7a64a] hover:text-[#d4b55a] inline-flex items-center gap-1.5 shrink-0 mt-1">
                    All 11 Chapters
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            <div class="overflow-hidden rounded-md border border-white/5">
                <div class="grid grid-cols-12 bg-[#0a0a0a] px-4 py-3 text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">
                    <div class="col-span-5">Chapter</div>
                    <div class="col-span-2 text-right">Reads</div>
                    <div class="col-span-2 text-right">Completion</div>
                    <div class="col-span-1 text-right">Comments</div>
                    <div class="col-span-2 text-right">Rating</div>
                </div>
                @foreach($chapterTable as $row)
                <div class="grid grid-cols-12 items-center px-4 py-3.5 border-t border-white/5 hover:bg-white/[0.015] transition-colors">
                    <div class="col-span-5 text-[13px] text-[#f2efe8] font-medium truncate">
                        <span class="text-[#a3a3a3] mr-1.5 font-semibold">{{ $row['num'] }} ·</span>{{ $row['title'] }}
                    </div>
                    <div class="col-span-2 text-right text-[12px] text-[#f2efe8] tabular-nums font-semibold">{{ $row['reads'] }}</div>
                    <div class="col-span-2 text-right text-[12px] text-[#f2efe8] tabular-nums">{{ $row['completion'] }}</div>
                    <div class="col-span-1 text-right text-[12px] text-[#f2efe8] tabular-nums">{{ $row['comments'] }}</div>
                    <div class="col-span-2 text-right flex items-center justify-end gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#c7a64a]" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <span class="text-[12px] text-[#c7a64a] tabular-nums font-bold">{{ $row['rating'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <aside class="space-y-6 min-w-0">

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-2">Discovery</p>
            <h3 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight mb-1">How readers find you</h3>
            <p class="mb-4 text-[11.5px] text-[#a3a3a3]">Share of chapter reads</p>

            <div class="space-y-4">
                @foreach($discovery as $d)
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-[12.5px] text-[#f2efe8]">{{ $d['name'] }}</p>
                        <p class="text-[11.5px] font-bold text-[#c7a64a] tabular-nums">{{ $d['pct'] }}%</p>
                    </div>
                    <div class="h-[4px] w-full rounded-full bg-white/5 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#c7a64a] to-[#d4b55a]" style="width: {{ $d['pct'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-2">Reader Engagement</p>
            <h3 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight mb-1">The story holds them</h3>
            <p class="text-[32px] font-serif font-medium text-[#f2efe8] leading-none mb-1.5 tracking-tight">68%</p>
            <p class="mb-4 text-[10.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Returning Readers</p>
            <p class="mb-6 text-[12px] text-[#a3a3a3] leading-relaxed">More than two thirds of your audience returned for another chapter this week.</p>

            <div class="space-y-3.5 border-t border-white/5 pt-4">
                <div class="flex items-center justify-between">
                    <p class="text-[12px] text-[#a3a3a3]">Average reading session</p>
                    <p class="text-[12px] font-bold text-[#c7a64a] tabular-nums">18m 42s</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-[12px] text-[#a3a3a3]">Added to reading shelves</p>
                    <p class="text-[12px] font-bold text-[#c7a64a] tabular-nums">216</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-[12px] text-[#a3a3a3]">New author followers</p>
                    <p class="text-[12px] font-bold text-[#c7a64a] tabular-nums">84</p>
                </div>
            </div>

            <div class="mt-5">
                <span class="inline-flex rounded-sm border border-[#c7a64a]/30 px-2.5 py-1 text-[9px] font-bold uppercase tracking-[0.18em] text-[#c7a64a]">
                    Chapter 11 · Highest Completion
                </span>
            </div>
        </div>

    </aside>
</div>

@endsection
