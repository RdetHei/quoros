@extends('layouts.writer', [
    'active' => $active ?? 'chapters',
])

@section('dashboard-content')

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
                        <p class="text-[10px] uppercase tracking-[0.22em] text-[#a3a3a3] font-semibold mt-0.5">Part II · The Northern House</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 rounded-sm border border-white/5 hover:border-white/15 hover:bg-white/5 text-[#a3a3a3] hover:text-[#f2efe8] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </button>
                    <button class="p-2 rounded-sm border border-white/5 hover:border-white/15 hover:bg-white/5 text-[#a3a3a3] hover:text-[#f2efe8] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between px-5 py-3 border-b border-white/5 bg-[#0a0a0a]">
                <span class="rounded-sm border border-white/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#f2efe8]">
                    Chapter 12 of 24
                </span>
                <div class="flex items-center gap-5">
                    <span class="inline-flex items-center gap-1.5 text-[10.5px] font-semibold text-[#a3a3a3]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        Autosaved Just Now
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-[#f2efe8] tabular-nums">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        1,284 words
                    </span>
                </div>
            </div>

            <div class="px-6 sm:px-12 md:px-20 py-10 md:py-14 max-w-full">
                <div class="mx-auto max-w-[65ch]">
                    <p class="text-center text-[10px] font-bold uppercase tracking-[0.3em] text-[#c7a64a] mb-4">Chapter Twelve</p>
                    <h1 class="font-serif text-[34px] md:text-[38px] text-center text-[#f2efe8] tracking-tight mb-4">
                        The Language of Frost
                    </h1>
                    <div class="mx-auto w-10 h-px bg-[#c7a64a]/60 mb-10"></div>

                    <div class="space-y-6 text-[15.5px] md:text-[16px] leading-[1.95] text-[#e5dfd0] font-serif">
                        <p class="first-letter:font-serif first-letter:text-[56px] first-letter:leading-none first-letter:mr-2 first-letter:mt-1.5 first-letter:float-left first-letter:text-[#c7a64a] first-letter:font-medium">
                            The orchard had learned a new language overnight. Every branch spoke in silver, every fallen apple held beneath its skin the small, bright silence of winter.
                        </p>
                        <p>
                            Elian crossed the lower field before dawn, carrying his mother's brass key in the warm hollow of his palm. The house beyond the trees showed only one light—the high eastern window, where no one had slept in twenty years.
                        </p>
                        <p>
                            <span class="italic">"You came back,"</span> said a voice behind him.
                        </p>
                        <p>
                            Mara stood at the gate in her red coat, her hair pinned carelessly against the wind. Snow had gathered on her shoulders like a pair of pale wings. He wanted to tell her that leaving had been easier than remembering. Instead, he opened his hand.
                        </p>
                        <p>
                            The key caught the first light. For a moment, neither of them moved.
                        </p>
                    </div>

                    <div class="mt-12 flex items-center justify-center gap-3">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>
                        <div class="h-px w-24 bg-gradient-to-r from-transparent via-[#c7a64a]/30 to-transparent"></div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/5 bg-[#0a0a0a] px-5 py-4 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap gap-2.5">
                    <button class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                        Save Draft
                    </button>
                    <button class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        Preview
                    </button>
                </div>
                <div class="flex flex-wrap gap-2.5">
                    <button class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Schedule
                    </button>
                    <button class="inline-flex items-center gap-2 rounded-md bg-[#c7a64a] px-5 py-2.5 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">
                        Publish
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <aside class="space-y-5 min-w-0">

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <h3 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight">Novel Hub</h3>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        <span class="text-[9.5px] font-bold uppercase tracking-[0.2em] text-amber-400">Live</span>
                    </span>
                </div>
            </div>

            <div class="mb-5">
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Platform Activity</p>
                <div class="flex items-start justify-between mb-1">
                    <h4 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight">Performance</h4>
                    <span class="inline-flex items-center gap-1 mt-1 rounded border border-[#c7a64a]/30 px-2 py-0.5 text-[9.5px] font-bold text-[#c7a64a]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        18.4%
                    </span>
                </div>
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3] mb-3">Reader Activity · Last 7 Days</p>

                <div class="flex items-baseline gap-3 mb-4">
                    <p class="text-[32px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight">12.8k</p>
                    <span class="text-[11px] text-[#a3a3a3] mb-0.5">reads</span>
                    <div class="ml-auto flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#c7a64a]" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <span class="text-[12.5px] font-bold text-[#f2efe8] tabular-nums">4.8</span>
                        <span class="text-[10.5px] text-[#a3a3a3]">[326]</span>
                    </div>
                </div>

                <svg viewBox="0 0 300 80" class="w-full h-[72px]" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="perfFill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#c7a64a" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#c7a64a" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <polygon fill="url(#perfFill)" points="10,58 55,50 100,44 145,40 190,35 235,28 285,18 285,72 10,72"/>
                    <polyline fill="none" stroke="#c7a64a" stroke-width="2.2" points="10,58 55,50 100,44 145,40 190,35 235,28 285,18"/>
                    @foreach([[10,58],[55,50],[100,44],[145,40],[190,35],[235,28],[285,18]] as $pt)
                        <circle cx="{{$pt[0]}}" cy="{{$pt[1]}}" r="2.5" fill="#c7a64a"/>
                    @endforeach
                </svg>
                <div class="mt-1 grid grid-cols-7 text-center text-[8.5px] uppercase tracking-[0.22em] text-[#a3a3a3] font-semibold">
                    @foreach(['M','T','W','T','F','S','S'] as $d) <span>{{$d}}</span> @endforeach
                </div>
            </div>
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Writing</p>
                    <h3 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight">Chapters</h3>
                    <p class="mt-1 text-[11px] text-[#a3a3a3]">
                        <span class="font-bold text-[#f2efe8] tabular-nums">24</span> chapters · <span class="font-bold text-[#f2efe8] tabular-nums">68,420</span> words
                    </p>
                </div>
                <button class="flex h-7 w-7 shrink-0 items-center justify-center rounded-sm border border-white/10 text-[#f2efe8] hover:border-[#c7a64a]/40 hover:bg-[#c7a64a]/10 hover:text-[#c7a64a] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                </button>
            </div>

            <div class="space-y-1.5 mb-4 max-h-[340px] overflow-y-auto custom-scrollbar pr-1">
                @foreach($chaptersList as $ch)
                <div class="flex items-start gap-2.5 rounded-md border {{ !empty($ch['active']) ? 'border-[#c7a64a]/30 bg-[#c7a64a]/8' : 'border-white/5 bg-transparent hover:border-white/10 hover:bg-white/[0.02]' }} p-3 transition-all cursor-pointer group">
                    <span class="mt-0.5 text-[#a3a3a3] cursor-grab group-hover:text-[#c7a64a] shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"/></svg>
                    </span>
                    <div class="flex shrink-0 h-7 w-7 items-center justify-center rounded-sm border {{ !empty($ch['active']) ? 'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' : 'border-white/10 text-[#a3a3a3]' }} text-[11px] font-bold tabular-nums">
                        {{ $ch['num'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[12.5px] font-medium text-[#f2efe8] truncate">{{ $ch['title'] }}</p>
                        <p class="mt-0.5 text-[10px] text-[#a3a3a3] tabular-nums">{{ $ch['words'] }}</p>
                    </div>
                    <span class="shrink-0 mt-0.5 rounded-sm border {{ $ch['status_class'] === 'published' ? 'border-[#c7a64a]/30 text-[#c7a64a]' : 'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' }} px-2 py-0.5 text-[8.5px] font-bold uppercase tracking-[0.15em]">
                        {{ $ch['status'] }}
                    </span>
                </div>
                @endforeach
            </div>

            <a href="#" class="flex items-center justify-center gap-1.5 py-2 text-[10.5px] font-bold uppercase tracking-[0.2em] text-[#c7a64a] hover:text-[#d4b55a] transition-colors">
                View All Chapters
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
                            Main Cast · 12 Profiles
                        </p>
                    </div>
                    <button class="flex h-7 w-7 shrink-0 items-center justify-center rounded-sm border border-white/10 text-[#f2efe8] hover:border-[#c7a64a]/40 hover:bg-[#c7a64a]/10 hover:text-[#c7a64a] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </button>
                </div>

                <div class="flex gap-3 -space-x-1 mb-4">
                    @foreach([
                        ['Elian','from-amber-800/70 to-stone-700'],
                        ['Mara','from-red-900/70 to-amber-800/60'],
                        ['Beatrice','from-stone-500/80 to-neutral-700'],
                        ['Jonas','from-stone-700 to-amber-950'],
                        ['Iris','from-amber-700/70 to-stone-600'],
                    ] as [$name,$col])
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="h-11 w-11 rounded-full border-2 border-[#121212] bg-gradient-to-br {{$col}} flex items-center justify-center text-[11px] font-bold text-[#f2efe8] shadow-lg">
                                {{ strtoupper(substr($name,0,1)) }}
                            </div>
                            <p class="text-[10.5px] font-semibold text-[#f2efe8]">{{$name}}</p>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between mt-3 pt-3 border-t border-white/5">
                    <p class="inline-flex items-center gap-1.5 text-[10.5px] text-[#a3a3a3]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                        <span>3 unresolved relationships</span>
                    </p>
                    <a href="#" class="text-[10.5px] font-bold uppercase tracking-[0.2em] text-[#c7a64a] hover:text-[#d4b55a] inline-flex items-center gap-1.5">
                        Open Codex
                    </a>
                </div>
            </div>
        </div>

    </aside>
</div>

@endsection
