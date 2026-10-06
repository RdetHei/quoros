@extends('layouts.writer')

@section('dashboard-content')
<div class="space-y-5">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2 flex-wrap">
            @foreach($tabs as $t)
                <button @class([
                    'px-4 py-2 rounded-sm border text-xs uppercase tracking-[0.15em] transition-colors',
                    $t['active'] ?? false ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10 text-[#c7a64a]' : 'border-white/10 bg-[#121212] text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]'
                ])>
                    {{ $t['label'] }} · <span class="ml-0.5">{{ $t['count'] }}</span>
                </button>
            @endforeach
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <span class="px-3 py-1.5 rounded-sm border border-white/10 text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] bg-[#121212]">LAST 7 DAYS</span>
            <button class="px-4 py-2 rounded-sm border border-white/10 bg-[#121212] text-[#a3a3a3] text-xs uppercase tracking-widest hover:bg-white/5 transition-colors inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                ALL CHAPTERS
            </button>
            <button class="px-4 py-2 rounded-sm border border-white/10 bg-[#121212] text-[#a3a3a3] text-xs uppercase tracking-widest hover:bg-white/5 transition-colors inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                MARK ALL READ
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,420px)_minmax(0,1fr)] gap-5">
        <div class="space-y-4">
            <div class="rounded-sm border border-white/10 bg-[#121212] overflow-hidden">
                <div class="p-4 border-b border-white/10">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#a3a3a3] absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <input placeholder="Search reader feedback..." class="w-full pl-11 pr-4 py-3 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">
                    </div>
                </div>
                <div class="p-4 space-y-4 max-h-[720px] overflow-y-auto">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#c7a64a]">THE GLASS ORCHARD</p>
                        <button class="text-[10px] uppercase tracking-[0.15em] text-[#a3a3a3] hover:text-[#f2efe8] inline-flex items-center gap-1">
                            NEWEST FIRST
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" /></svg>
                        </button>
                    </div>

                    @foreach($conversations as $c)
                        <a href="#" @class([
                            'block p-4 rounded-sm border transition-all group',
                            $c['active'] ?? false ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10' : 'border-white/10 bg-[#0a0a0a] hover:border-white/20 hover:bg-white/5'
                        ])>
                            <div class="flex items-start gap-3">
                                <div class="relative">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-serif text-[14px] text-[#f2efe8] shrink-0" style="background: {{$c['color']}}/20; border: 1px solid {{$c['color']}}/40;">
                                        {{ $c['initials'] }}
                                    </div>
                                    @if($c['new'] ?? false)
                                        <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full border-2 border-[#0a0a0a]" style="background: #c7a64a;"></span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <h4 class="text-[15px] text-[#f2efe8] font-medium">{{ $c['name'] }}</h4>
                                        <span class="text-[10px] text-[#a3a3a3] whitespace-nowrap">{{ $c['time'] }}</span>
                                    </div>
                                    <p class="text-[13px] text-[#d4cfc2] leading-relaxed line-clamp-2 mb-2">{{ $c['text'] }}</p>
                                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#a3a3a3]">{{ $c['chapter'] }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach

                    <div class="pt-2 border-t border-white/10 text-center">
                        <p class="text-[11px] text-[#a3a3a3] mb-2">6 OF 184 CONVERSATIONS</p>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#a3a3a3] mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-sm border border-white/10 bg-[#121212] overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-white/10 flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.25em] text-[#c7a64a] mb-1.5">{{ $activeThread['chapter'] }}</p>
                    <h2 class="font-serif text-[24px] text-[#f2efe8]">{{ $activeThread['title'] }}</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-sm border border-[#c7a64a]/40 text-[10px] uppercase tracking-[0.22em] text-[#c7a64a] bg-[#c7a64a]/5 whitespace-nowrap">{{ $activeThread['status_badge'] }}</span>
                    <button class="p-2 rounded-sm border border-white/10 text-[#a3a3a3] hover:text-[#f2efe8] hover:bg-white/5 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" /></svg>
                    </button>
                    <button class="p-2 rounded-sm border border-white/10 text-[#a3a3a3] hover:text-[#f2efe8] hover:bg-white/5 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 p-6 md:p-8 space-y-8 overflow-y-auto max-h-[720px]">
                @foreach($activeThread['comments'] as $cmt)
                    @if($cmt['type'] === 'reader')
                        <div class="@if($cmt['indented'] ?? false) ml-8 pl-5 border-l border-[#c7a64a]/30 @endif">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-11 h-11 rounded-full flex items-center justify-center font-serif text-[15px] text-[#f2efe8] shrink-0" style="background: {{$cmt['color']}}/20; border: 1px solid {{$cmt['color']}}/40;">
                                    {{ $cmt['initials'] }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap mb-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h5 class="text-[15px] text-[#f2efe8] font-medium">{{ $cmt['name'] }}</h5>
                                            @if(!($cmt['indented'] ?? false))
                                                <span class="text-[10px] uppercase tracking-[0.22em] text-[#a3a3a3] px-2.5 py-0.5 rounded-sm border border-white/10 bg-[#0a0a0a]">FOLLOWING</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-[10px] uppercase tracking-[0.22em] text-[#a3a3a3]">{{ $cmt['meta'] }}</p>
                                </div>
                            </div>
                            <p class="font-serif text-[16px] text-[#e8e3d6] leading-[1.8] mb-4">{{ $cmt['text'] }}</p>
                            <div class="flex items-center gap-4 text-xs">
                                <button class="inline-flex items-center gap-1.5 text-[#a3a3a3] hover:text-[#c7a64a] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="#c7a64a" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                                    <span class="text-[#c7a64a] font-medium">{{ $cmt['likes'] }} LIKES</span>
                                </button>
                                <button class="text-[#c7a64a] hover:underline font-medium tracking-wide">Reply</button>
                                <button class="text-[#a3a3a3] hover:text-[#f2efe8] inline-flex items-center gap-1 transition-colors">
                                    View chapter
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="rounded-sm border border-[#c7a64a]/40 bg-[#0a0a0a] overflow-hidden">
                            <div class="px-5 py-3.5 border-b border-[#c7a64a]/20 flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    <span class="font-serif text-[15px] text-[#f2efe8]">{{ $cmt['name'] }}</span>
                                </div>
                                <span class="text-[10px] uppercase tracking-[0.22em] text-[#c7a64a] px-2.5 py-0.5 rounded-sm border border-[#c7a64a]/30 bg-[#c7a64a]/5">{{ $cmt['status'] }}</span>
                            </div>
                            <div class="p-5">
                                <p class="font-serif text-[16px] text-[#e8e3d6] leading-[1.8] mb-4">{{ $cmt['text'] }}</p>
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <p class="text-[10px] uppercase tracking-[0.22em] text-[#a3a3a3]">{{ $cmt['footer'] }}</p>
                                    <button class="px-5 py-2.5 bg-[#c7a64a] text-black text-xs uppercase tracking-[0.2em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors inline-flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                                        Send Reply
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="pt-2 border-t border-white/10 flex items-center justify-between gap-3 flex-wrap">
                    <p class="text-xs text-[#a3a3a3]">Keep the conversation thoughtful and spoiler-aware.</p>
                    <button class="px-4 py-2 rounded-sm border border-white/10 text-[#a3a3a3] text-xs uppercase tracking-[0.2em] hover:bg-white/5 hover:text-[#f2efe8] transition-colors inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        RESOLVE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
