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
        <button class="px-4 py-2 bg-[#c7a64a] text-black text-xs uppercase tracking-[0.15em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New Note
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] gap-5">
        <div class="space-y-4">
            <div class="rounded-sm border border-white/10 bg-[#121212] overflow-hidden">
                <div class="p-4 border-b border-white/10">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#a3a3a3] absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <input placeholder="Search notes..." class="w-full pl-11 pr-4 py-3 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">
                    </div>
                </div>
                <div class="p-4 space-y-6">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#c7a64a] mb-3">THE GLASS ORCHARD</p>
                        <div class="space-y-1">
                            @foreach($notebookGroups as $g => $c)
                                <button class="w-full flex items-center justify-between p-2.5 rounded-sm hover:bg-white/5 text-[#f2efe8] text-sm group transition-colors">
                                    <span class="inline-flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#a3a3a3] group-hover:text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                        {{ $g }}
                                    </span>
                                    <span class="text-[11px] text-[#a3a3a3]">{{ $c }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-3">RECENTLY EDITED</p>
                        <div class="space-y-2">
                            @foreach($recentNotes as $n)
                                <a href="#" @class([
                                    'block p-3.5 rounded-sm border transition-all group',
                                    $n['active'] ?? false ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10' : 'border-white/10 bg-[#0a0a0a] hover:border-white/20 hover:bg-white/5'
                                ])>
                                    <div class="flex items-start justify-between gap-2 mb-1.5">
                                        <h4 class="font-serif text-[16px] text-[#f2efe8] leading-snug flex-1">{{ $n['title'] }}</h4>
                                        @if($n['pinned'] ?? false)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                                        @endif
                                    </div>
                                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#a3a3a3]">{{ $n['cat'] }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <p class="text-xs text-[#a3a3a3] pt-2">All notes are private to your studio.</p>
                </div>
            </div>
        </div>

        <div class="rounded-sm border border-white/10 bg-[#121212] overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between gap-2 flex-wrap">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    <h3 class="font-serif text-[20px] text-[#f2efe8]">{{ $activeNote['title'] }}</h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-sm border border-[#c7a64a]/30 text-[10px] uppercase tracking-[0.22em] text-[#c7a64a] bg-[#c7a64a]/5 whitespace-nowrap">SAVED JUST NOW</span>
                    <button class="p-2 rounded-sm border border-white/10 text-[#a3a3a3] hover:text-[#f2efe8] hover:bg-white/5 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                    </button>
                    <button class="p-2 rounded-sm border border-white/10 text-[#a3a3a3] hover:text-[#f2efe8] hover:bg-white/5 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-3 border-b border-white/10 flex items-center gap-2 flex-wrap">
                <button class="px-3 py-1.5 rounded-sm border border-white/10 text-[#a3a3a3] text-xs uppercase tracking-widest hover:bg-white/5 inline-flex items-center gap-2">
                    Body Text
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div class="w-px h-5 bg-white/10"></div>
                @foreach(['B','I','≡'] as $t)
                    <button class="px-3 py-1.5 rounded-sm border border-white/10 text-[#a3a3a3] text-xs font-semibold hover:bg-white/5 transition-colors">{{ $t }}</button>
                @endforeach
                <button class="px-3 py-1.5 rounded-sm border border-white/10 text-[#a3a3a3] hover:bg-white/5 transition-colors inline-flex items-center justify-center" title="Insert Link">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                </button>
                @foreach(['¶'] as $t)
                    <button class="px-3 py-1.5 rounded-sm border border-white/10 text-[#a3a3a3] text-xs font-semibold hover:bg-white/5 transition-colors">{{ $t }}</button>
                @endforeach
            </div>

            <div class="flex-1 p-6 md:p-10 space-y-8 overflow-y-auto max-h-[640px]">
                <div class="max-w-[780px] mx-auto space-y-8">
                    <div class="flex items-center gap-2 mb-6 flex-wrap">
                        @foreach($activeNote['tags'] as $t)
                            <span class="px-2.5 py-1 rounded-sm border border-white/10 text-[9px] uppercase tracking-[0.2em] text-[#a3a3a3] bg-[#0a0a0a]">{{ $t }}</span>
                        @endforeach
                    </div>

                    <div class="mb-10">
                        <h1 class="font-serif text-[38px] md:text-[44px] text-[#f2efe8] leading-[1.1]">{{ $activeNote['doc_title'] }}</h1>
                        <p class="text-[10px] uppercase tracking-[0.22em] text-[#a3a3a3] mt-4 pb-6 border-b border-white/10">{{ $activeNote['updated'] }}</p>
                    </div>

                    @foreach($activeNote['sections'] as $s)
                        @if(isset($s['highlight']))
                            <div class="rounded-sm border border-[#c7a64a]/30 bg-[#c7a64a]/5 p-6">
                                <p class="text-[10px] uppercase tracking-[0.25em] text-[#c7a64a] mb-3">{{ $s['highlight'] }}</p>
                                <p class="font-serif text-[16px] text-[#e8e3d6] leading-[1.8] italic">{{ $s['body'] }}</p>
                            </div>
                        @else
                            <div>
                                <h2 class="font-serif text-[24px] text-[#f2efe8] mb-4">{{ $s['heading'] }}</h2>
                                <p class="text-[14px] text-[#d4cfc2] leading-[1.9]">{{ $s['body'] }}</p>
                            </div>
                        @endif
                    @endforeach

                    <div class="pt-8 border-t border-white/10 flex items-center justify-between gap-4 flex-wrap">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mr-2">LINKED TO THIS NOTE</p>
                            @foreach($activeNote['linked'] as $l)
                                <span class="px-2.5 py-1 rounded-sm border border-[#c7a64a]/30 text-[10px] uppercase tracking-[0.15em] text-[#c7a64a] bg-[#c7a64a]/5">{{ $l }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-white/10 flex items-center justify-between gap-3 flex-wrap">
                <p class="text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3]">{{ $activeNote['footer'] }}</p>
                <button class="px-4 py-2 rounded-sm border border-white/10 text-[#a3a3a3] text-xs uppercase tracking-[0.2em] hover:bg-white/5 hover:text-[#f2efe8] transition-colors inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                    LINK TO CHAPTER
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
