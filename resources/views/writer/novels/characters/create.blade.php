@extends('layouts.writer')

@section('content')
<div class="max-w-5xl">
    <div class="rounded-sm border border-white/10 bg-[#121212] overflow-hidden">
        <div class="px-6 md:px-8 py-5 border-b border-white/10 flex items-center justify-between gap-3 flex-wrap">
            <div>
                <p class="text-[10px] uppercase tracking-[0.25em] text-[#c7a64a]">NEW CHARACTER PROFILE</p>
                <p class="text-[13px] text-[#a3a3a3] mt-1">Joining <span class="text-[#f2efe8]">{{ $novel->title }}</span></p>
            </div>
            <a href="{{ route('writer.codex', ['novel_id' => $novel->id]) }}" class="inline-flex items-center gap-2 text-[11px] uppercase tracking-[0.2em] text-[#a3a3a3] hover:text-[#c7a64a] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Character Codex
            </a>
        </div>

        <div class="p-6 md:p-8">
            @include('writer.novels.characters._form')
        </div>
    </div>
</div>
@endsection
