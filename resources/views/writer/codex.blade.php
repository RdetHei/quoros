@extends('layouts.writer')

@section('dashboard-content')
@php
    $visibleCast = $visibleCast ?? collect();
    $otherCharacters = $otherCharacters ?? collect();
    $unresolved = $unresolved ?? 0;
    $supportingCount = $supportingCount ?? 0;
@endphp
<div class="space-y-5" x-data="{ q: '' }">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2 flex-wrap">
            @foreach($tabs as $t)
                <a href="{{ route('writer.codex', array_filter(['novel_id' => $selectedNovel?->id, 'filter' => $t['key'], 'character_id' => $selectedCharacter['id'] ?? null])) }}"
                   @class([
                       'px-4 py-2 rounded-sm border text-xs uppercase tracking-[0.15em] transition-colors',
                       $filter === $t['key'] ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10 text-[#c7a64a]' : 'border-white/10 bg-[#121212] text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]'
                   ])>
                    {{ $t['label'] }} · <span class="ml-0.5">{{ $t['count'] }}</span>
                </a>
            @endforeach
        </div>
        <div class="flex items-center gap-2">
            <div class="px-3 py-1.5 rounded-sm border border-[#c7a64a]/30 bg-[#c7a64a]/5 text-[10px] uppercase tracking-[0.2em] text-[#c7a64a]">{{ $unresolved }} UNRESOLVED RELATIONSHIPS</div>
            @if($selectedNovel)
                <a href="{{ route('writer.novels.characters.create', $selectedNovel) }}" class="px-4 py-2 bg-[#c7a64a] text-black text-xs uppercase tracking-[0.15em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    New Character
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] gap-5">
        <div class="space-y-5">
            <div class="rounded-sm border border-white/10 bg-[#121212] p-5">
                <div class="relative mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#a3a3a3] absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    <input x-model="q" placeholder="Search the codex..." class="w-full pl-11 pr-4 py-3 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">
                </div>

                <p class="text-[10px] uppercase tracking-[0.2em] text-[#c7a64a] mb-3 mt-6">{{ $selectedNovel?->title ? strtoupper($selectedNovel->title).' · ' : '' }}{{ $filter === 'supporting' ? 'SUPPORTING CAST' : ($filter === 'main' ? 'MAIN CAST' : 'ALL PROFILES') }}</p>
                <div class="space-y-2">
                    @forelse($visibleCast as $c)
                        <a href="{{ route('writer.codex', array_filter(['novel_id' => $selectedNovel?->id, 'filter' => $filter, 'character_id' => $c['id']])) }}"
                           x-show="q === '' || '{{ strtolower(addslashes($c['name'].' '.$c['tagline'])) }}'.includes(q.toLowerCase())"
                           @class([
                               'block p-3 rounded-sm border transition-all group',
                               ($selectedCharacter && $selectedCharacter['id'] === $c['id']) ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10' : 'border-white/10 bg-[#0a0a0a] hover:border-white/20 hover:bg-white/5'
                           ])>
                            <div class="flex items-start gap-3">
                                <div class="w-11 h-11 rounded-full flex items-center justify-center font-serif text-[17px] text-[#f2efe8] shrink-0" style="background: radial-gradient(circle at 30% 30%, {{$c['color']}}/60, #121212); border: 1px solid {{$c['color']}}/40;">
                                    {{ $c['initials'] }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="font-serif text-[18px] text-[#f2efe8] leading-none">{{ $c['name'] }}</h3>
                                        @if($selectedCharacter && $selectedCharacter['id'] === $c['id'])
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#c7a64a]"></span>
                                        @endif
                                    </div>
                                    <p class="text-[12px] text-[#a3a3a3] mt-1.5 line-clamp-1">{{ $c['tagline'] }}</p>
                                    <p class="text-[9px] uppercase tracking-[0.2em] text-[#c7a64a] mt-2">{{ $c['badge'] }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-[13px] text-[#a3a3a3] px-1 py-2">No profiles in this view.</p>
                    @endforelse
                </div>

                <div class="my-6 border-t border-white/10"></div>

                <div class="flex items-center justify-between px-1">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3]">SUPPORTING CAST · {{ $supportingCount }} PROFILES</p>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </div>
            </div>

            @if($unresolved > 0)
                <div class="rounded-sm border border-[#c7a64a]/30 bg-gradient-to-br from-[#c7a64a]/10 via-[#c7a64a]/5 to-transparent p-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#c7a64a] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                    <h3 class="font-serif text-[22px] text-[#f2efe8] leading-snug mb-2">Some ties are still unwritten.</h3>
                    <p class="text-[13px] text-[#a3a3a3] leading-relaxed">Review the {{ $unresolved }} open relationship {{ $unresolved === 1 ? 'question' : 'questions' }} before your next revision.</p>
                </div>
            @endif

            <p class="text-xs text-[#a3a3a3] px-1">Character profiles and planning notes are private.</p>
        </div>

        <div class="rounded-sm border border-white/10 bg-[#121212] overflow-hidden">
            @if(! $selectedCharacter)
                <div class="px-6 py-4 border-b border-white/10">
                    <p class="text-[10px] uppercase tracking-[0.25em] text-[#c7a64a]">CHARACTER PROFILE</p>
                </div>
                <div class="p-10 md:p-16 flex flex-col items-center justify-center text-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    <h3 class="font-serif text-[24px] text-[#f2efe8]">No character profiles yet.</h3>
                    <p class="text-[13px] text-[#a3a3a3] max-w-sm leading-relaxed">Give your story its people — add a character and start shaping their wants, fears, and bonds.</p>
                    @if($selectedNovel)
                        <a href="{{ route('writer.novels.characters.create', $selectedNovel) }}" class="px-4 py-2 bg-[#c7a64a] text-black text-xs uppercase tracking-[0.15em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            New Character
                        </a>
                    @endif
                </div>
            @else
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between flex-wrap gap-3">
                    <p class="text-[10px] uppercase tracking-[0.25em] text-[#c7a64a]">CHARACTER PROFILE / {{ strtoupper($selectedCharacter['name']) }}</p>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-sm border border-white/10 text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] bg-[#0a0a0a]">{{ strtoupper($selectedCharacter['cast_type']) }}</span>
                        @if($selectedNovel)
                            <a href="{{ route('writer.novels.characters.edit', [$selectedNovel, $selectedCharacter['id']]) }}" class="px-4 py-2 rounded-sm border border-white/10 text-[#a3a3a3] text-[11px] uppercase tracking-[0.2em] hover:bg-white/5 hover:text-[#f2efe8] inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                Edit Profile
                            </a>
                        @endif
                    </div>
                </div>

                <div class="p-6 md:p-8 space-y-8">
                    <div class="flex items-start gap-5">
                        @if(! empty($selectedCharacter['image_url']))
                            <img src="{{ $selectedCharacter['image_url'] }}" alt="{{ $selectedCharacter['name'] }}" class="w-28 h-28 shrink-0 rounded-full object-cover" style="border: 2px solid {{ $selectedCharacter['color'] }}/40;">
                        @else
                            <div class="w-28 h-28 shrink-0 rounded-full flex items-center justify-center font-serif text-[44px] text-[#f2efe8]" style="background: radial-gradient(circle at 30% 30%, {{ $selectedCharacter['color'] }}/50, #0a0a0a); border: 2px solid {{ $selectedCharacter['color'] }}/40;">
                                {{ $selectedCharacter['initials'] }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4 flex-wrap">
                                <h1 class="font-serif text-[44px] text-[#f2efe8] leading-none">{{ $selectedCharacter['name'] }}</h1>
                                @if(! empty($selectedCharacter['badge']))
                                    <span class="px-3 py-1 rounded-sm border border-[#c7a64a]/40 text-[10px] uppercase tracking-[0.22em] text-[#c7a64a] bg-[#c7a64a]/5 whitespace-nowrap">{{ strtoupper($selectedCharacter['badge']) }}</span>
                                @endif
                            </div>
                            <p class="font-serif italic text-[18px] text-[#a3a3a3] mt-4 leading-relaxed">{{ $selectedCharacter['tagline'] }}</p>
                            @if(! empty($selectedCharacter['tags']))
                                <div class="flex items-center gap-2 flex-wrap mt-4">
                                    @foreach($selectedCharacter['tags'] as $t)
                                        <span class="px-2.5 py-1 rounded-sm border border-white/10 text-[10px] uppercase tracking-[0.15em] text-[#a3a3a3] bg-[#0a0a0a]">{{ $t }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="p-4 rounded-sm border border-white/10 bg-[#0a0a0a]">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Story Role</p>
                            <p class="font-serif text-[17px] text-[#f2efe8]">{{ $selectedCharacter['role'] }}</p>
                        </div>
                        <div class="p-4 rounded-sm border border-white/10 bg-[#0a0a0a]">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Anchor Setting</p>
                            <p class="font-serif text-[17px] text-[#f2efe8]">{{ $selectedCharacter['anchor'] }}</p>
                        </div>
                        <div class="p-4 rounded-sm border border-white/10 bg-[#0a0a0a]">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Current Chapter</p>
                            <p class="font-serif text-[17px] text-[#f2efe8]">{{ $selectedCharacter['current_chapter'] }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h2 class="font-serif text-[24px] text-[#f2efe8] mb-3">What they want</h2>
                            <p class="text-[14px] text-[#d4cfc2] leading-[1.8]">{{ $selectedCharacter['wants'] }}</p>
                        </div>
                        <div>
                            <h2 class="font-serif text-[24px] text-[#f2efe8] mb-3">What holds them back</h2>
                            <p class="text-[14px] text-[#d4cfc2] leading-[1.8]">{{ $selectedCharacter['fears'] }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-serif text-[24px] text-[#f2efe8]">The ties that shape them</h2>
                            <span class="text-[10px] uppercase tracking-[0.22em] text-[#c7a64a]">RELATIONSHIPS</span>
                        </div>
                        <div class="space-y-2">
                            @forelse($selectedCharacter['relationships'] as $r)
                                <div class="p-4 rounded-sm border border-white/10 bg-[#0a0a0a] flex items-start justify-between gap-4 flex-wrap">
                                    <div class="flex items-start gap-3 min-w-0 flex-1">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-serif text-[15px] text-[#f2efe8] shrink-0" style="background: radial-gradient(circle at 30% 30%, {{ $r['color'] }}/50, #121212); border: 1px solid {{ $r['color'] }}/40;">
                                            {{ $r['initials'] }}
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-serif text-[16px] text-[#f2efe8]">{{ $r['name'] }}</h4>
                                            <p class="text-[12px] text-[#a3a3a3] mt-0.5">{{ $r['note'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <span @class([
                                            'px-3 py-1 rounded-sm border text-[9px] uppercase tracking-[0.22em] whitespace-nowrap',
                                            $r['status'] === 'UNRESOLVED'
                                                ? 'border-[#c7a64a]/40 text-[#c7a64a] bg-[#c7a64a]/5'
                                                : 'border-white/10 text-[#a3a3a3]'
                                        ])>{{ $r['status'] }}</span>
                                        @if($selectedNovel)
                                            <form action="{{ route('writer.novels.relationships.destroy', [$selectedNovel, $r['id']]) }}" method="POST" onsubmit="return confirm('Hapus relasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" aria-label="Delete relationship" class="w-7 h-7 rounded-sm border border-white/10 text-[#a3a3a3] hover:text-[#f2efe8] hover:border-white/20 inline-flex items-center justify-center transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-[13px] text-[#a3a3a3] px-1 py-2">No relationships recorded yet.</p>
                            @endforelse
                        </div>

                        @if($selectedNovel)
                            <div class="mt-4 pt-4 border-t border-white/10" x-data="{ open: false }">
                                <button type="button" @click="open = ! open" class="inline-flex items-center gap-2 text-[11px] uppercase tracking-[0.2em] text-[#c7a64a] hover:text-[#d4b65a] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    Add relationship
                                </button>
                                <form x-show="open" x-cloak x-transition action="{{ route('writer.novels.relationships.store', $selectedNovel) }}" method="POST" class="mt-4 space-y-3">
                                    @csrf
                                    <input type="hidden" name="character_id" value="{{ $selectedCharacter['id'] }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <select name="related_character_id" class="w-full px-3 py-2 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] focus:outline-none focus:border-[#c7a64a]/40">
                                            <option value="">Linked character</option>
                                            @foreach($otherCharacters as $c)
                                                <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <select name="status" class="w-full px-3 py-2 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] focus:outline-none focus:border-[#c7a64a]/40">
                                            <option value="established">Established</option>
                                            <option value="unresolved">Unresolved</option>
                                        </select>
                                    </div>
                                    <input type="text" name="note" maxlength="255" placeholder="How are they connected?" class="w-full px-3 py-2 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">
                                    <button type="submit" class="px-4 py-2 bg-[#c7a64a] text-black text-xs uppercase tracking-[0.15em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors">Save relationship</button>
                                </form>
                            </div>
                        @endif
                    </div>

                    @if(! empty($selectedCharacter['linked_note']))
                        <div class="flex items-center justify-between gap-3 pt-2 border-t border-white/10">
                            <div class="flex items-center gap-3 min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#c7a64a] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                                <p class="font-serif text-[15px] text-[#f2efe8] truncate">{{ $selectedCharacter['linked_note']['title'] }}</p>
                            </div>
                            <a href="{{ $selectedCharacter['linked_note']['url'] }}" class="text-[11px] uppercase tracking-[0.2em] text-[#a3a3a3] hover:text-[#c7a64a] transition-colors whitespace-nowrap inline-flex items-center gap-1">
                                {{ $selectedCharacter['linked_note']['link'] }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
