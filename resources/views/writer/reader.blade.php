@extends('layouts.writer')

@section('dashboard-content')
<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_380px] gap-6">
    <div class="space-y-6">
        <div class="flex items-center gap-3 flex-wrap">
            <button class="flex items-center gap-2 px-4 py-2 rounded-sm border border-white/10 bg-[#121212] text-[#f2efe8] text-sm hover:bg-white/5 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                CONTENTS
            </button>
            <div class="flex items-center gap-2 text-[10px] uppercase tracking-[0.18em] text-[#a3a3a3] rounded-sm border border-[#c7a64a]/25 px-3 py-1.5 bg-[#121212]">
                <span class="text-[#c7a64a]">CHAPTER 12 OF 24</span>
            </div>
            <div class="flex items-center gap-2 text-[10px] uppercase tracking-[0.18em] text-[#a3a3a3] rounded-sm border border-[#c7a64a]/40 px-3 py-1.5 bg-[#121212]">
                <span class="text-[#c7a64a]">DRAFT PREVIEW</span>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <button class="px-4 py-2 rounded-sm border border-white/10 bg-[#121212] text-[#a3a3a3] text-xs uppercase tracking-widest hover:bg-white/5 transition-colors">Serif · 20 Px</button>
                <button class="flex items-center gap-2 px-4 py-2 rounded-sm border border-white/10 bg-[#121212] text-[#a3a3a3] text-xs uppercase tracking-widest hover:bg-white/5 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                    Night
                </button>
                <button class="flex items-center gap-2 px-4 py-2 rounded-sm border border-white/10 bg-[#121212] text-[#a3a3a3] text-xs uppercase tracking-widest hover:bg-white/5 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" /></svg>
                    Focus
                </button>
            </div>
        </div>

        <div class="rounded-sm border border-white/10 bg-[#121212] p-10 md:p-16">
            <div class="max-w-[780px] mx-auto">
                <div class="text-center mb-12 space-y-4">
                    <p class="text-[10px] uppercase tracking-[0.25em] text-[#a3a3a3]">{{ $readerChapter['part'] }}</p>
                    <p class="text-xs uppercase tracking-[0.2em] text-[#c7a64a] font-medium">{{ $readerChapter['chapter_label'] }}</p>
                    <h1 class="font-serif text-[44px] md:text-[52px] text-[#f2efe8] leading-[1.1] mt-4">{{ $readerChapter['title'] }}</h1>
                    <div class="w-12 h-px bg-[#c7a64a]/50 mx-auto"></div>
                </div>

                <div class="font-serif text-[20px] leading-[1.9] text-[#e8e3d6] space-y-8">
                    @foreach($readerChapter['paragraphs'] as $i => $p)
                        <p class="@if($i === 0) first-letter:font-serif first-letter:text-[72px] first-letter:text-[#c7a64a] first-letter:float-left first-letter:leading-[0.85] first-letter:mr-2 first-letter:mt-2 @endif">{{ $p }}</p>
                    @endforeach
                </div>

                <div class="mt-20">
                    <div class="flex items-center gap-3 max-w-md mx-auto">
                        <div class="flex-1 h-px bg-white/10"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-[#c7a64a]"></div>
                        <div class="flex-1 h-px bg-white/10"></div>
                    </div>
                    <p class="text-center text-[10px] uppercase tracking-[0.25em] text-[#a3a3a3] mt-6">END OF AVAILABLE DRAFT PREVIEW</p>
                </div>
            </div>
        </div>

        <div class="rounded-sm border border-white/10 bg-[#121212] px-5 py-4 grid grid-cols-12 items-center gap-4">
            <a href="{{ route('writer.chapters') }}" class="col-span-12 md:col-span-4 flex items-center gap-3 group hover:opacity-100 opacity-70 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#a3a3a3]">{{ $readerChapter['prev']['label'] }}</p>
                    <p class="font-serif text-[15px] text-[#f2efe8] group-hover:text-[#c7a64a] transition-colors">{{ $readerChapter['prev']['title'] }}</p>
                </div>
            </a>
            <div class="col-span-12 md:col-span-4 text-center">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#c7a64a] mb-1">{{ $readerChapter['word_count'] }}</p>
                <a href="{{ route('writer.chapters') }}" class="text-xs text-[#a3a3a3] hover:text-[#f2efe8] transition-colors inline-flex items-center gap-1">
                    Return to writing
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                </a>
            </div>
            <a href="{{ route('writer.chapters') }}" class="col-span-12 md:col-span-4 flex items-center justify-end gap-3 group hover:opacity-100 opacity-70 transition-opacity">
                <div class="text-right">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#a3a3a3]">{{ $readerChapter['next']['label'] }}</p>
                    <p class="font-serif text-[15px] text-[#f2efe8] group-hover:text-[#c7a64a] transition-colors">{{ $readerChapter['next']['title'] }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>
        </div>
    </div>

    <div class="hidden lg:flex flex-col gap-4">
        <div class="rounded-sm border border-white/10 bg-[#121212] p-5">
            <p class="text-[10px] uppercase tracking-[0.18em] text-[#c7a64a] mb-4">Focus Preview</p>
            <div class="space-y-2">
                <div class="h-2 w-full rounded-full bg-white/5 overflow-hidden"><div class="h-full w-2/3 bg-[#c7a64a]/40"></div></div>
                <p class="text-xs text-[#a3a3a3]">Reading progress · 66% of chapter</p>
            </div>
        </div>
        <div class="rounded-sm border border-[#c7a64a]/25 bg-[#c7a64a]/5 p-5">
            <p class="text-[10px] uppercase tracking-[0.18em] text-[#c7a64a] mb-3">Reader Mode</p>
            <p class="font-serif text-[14px] text-[#f2efe8] italic leading-relaxed mb-4">"The first light on the brass key should be where the exchange ends, not begins."</p>
            <a href="{{ route('writer.plot') }}" class="text-[10px] uppercase tracking-[0.2em] text-[#c7a64a] hover:underline">View linked note →</a>
        </div>
    </div>
</div>
@endsection
