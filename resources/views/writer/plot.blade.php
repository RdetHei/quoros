@extends('layouts.writer')

@section('dashboard-content')
<div class="space-y-5" x-data="{ q: '', showNew: false, editOpen: false }">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2 flex-wrap">
            @foreach($tabs as $t)
                <a href="{{ route('writer.plot', array_filter(['novel_id' => $selectedNovel?->id, 'filter' => $t['key'], 'note_id' => $activeNote['id'] ?? null])) }}"
                   @class([
                       'px-4 py-2 rounded-sm border text-xs uppercase tracking-[0.15em] transition-colors',
                       $filter === $t['key'] ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10 text-[#c7a64a]' : 'border-white/10 bg-[#121212] text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]'
                   ])>
                    {{ $t['label'] }} · <span class="ml-0.5">{{ $t['count'] }}</span>
                </a>
            @endforeach
        </div>
        <button type="button" @click="showNew = true" class="px-4 py-2 bg-[#c7a64a] text-black text-xs uppercase tracking-[0.15em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors inline-flex items-center gap-2">
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
                        <input x-model="q" placeholder="Search notes..." class="w-full pl-11 pr-4 py-3 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">
                    </div>
                </div>
                <div class="p-4 space-y-6">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#c7a64a] mb-3">{{ $selectedNovel?->title ?? 'Notebooks' }}</p>
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
                                <a href="{{ route('writer.plot', array_filter(['novel_id' => $selectedNovel?->id, 'filter' => $filter, 'note_id' => $n['id']])) }}"
                                   x-show="q === '' || @js($n['title'] . ' ' . $n['cat']).toLowerCase().includes(q.toLowerCase())"
                                   @class([
                                       'block p-3.5 rounded-sm border transition-all group',
                                       $n['active'] ?? false ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10' : 'border-white/10 bg-[#0a0a0a] hover:border-white/20 hover:bg-white/5'
                                   ])>
                                    <div class="flex items-start justify-between gap-2 mb-1.5">
                                        <h4 class="font-serif text-[16px] text-[#f2efe8] leading-snug flex-1">{{ $n['title'] }}</h4>
                                        @if($n['pinned'] ?? false)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                                        @endif
                                    </div>
                                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#a3a3a3]">
                                        {{ $n['cat'] }}@if($n['is_open'] ?? false)<span class="text-[#c7a64a]"> · Open question</span>@endif
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <p class="text-xs text-[#a3a3a3] pt-2">All notes are private to your studio.</p>
                </div>
            </div>
        </div>

        <div class="rounded-sm border border-white/10 bg-[#121212] overflow-hidden flex flex-col">
            @if(!$activeNote)
                <div class="flex-1 flex flex-col items-center justify-center gap-4 p-12 md:p-16 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    <p class="font-serif text-[20px] text-[#f2efe8]">No notes yet.</p>
                    <p class="text-xs text-[#a3a3a3] max-w-xs">Capture the first thread of your story and it will appear here.</p>
                    <button type="button" @click="showNew = true" class="px-4 py-2 bg-[#c7a64a] text-black text-xs uppercase tracking-[0.15em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        New Note
                    </button>
                </div>
            @else
                @php
                    $catLabel = match ($activeNote['category']) {
                        'scene_outline' => 'Scene outline',
                        'character_arc' => 'Character arc',
                        'world' => 'World & motifs',
                        'continuity' => 'Continuity',
                        default => 'Story note',
                    };
                @endphp
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                        <h3 class="font-serif text-[20px] text-[#f2efe8]">{{ $activeNote['title'] }}</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-sm border border-[#c7a64a]/30 text-[10px] uppercase tracking-[0.22em] text-[#c7a64a] bg-[#c7a64a]/5 whitespace-nowrap">{{ $activeNote['updated'] }}</span>
                        <form action="{{ route('writer.plot.pin', $activeNote['id']) }}" method="POST" class="inline-flex">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-sm border border-white/10 text-[#a3a3a3] text-xs uppercase tracking-[0.15em] hover:text-[#c7a64a] hover:bg-white/5 transition-colors inline-flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                                {{ $activeNote['is_pinned'] ? 'Unpin' : 'Pin' }}
                            </button>
                        </form>
                        <form action="{{ route('writer.plot.destroy', $activeNote['id']) }}" method="POST" class="inline-flex" onsubmit="return confirm('Hapus catatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-sm border border-white/10 text-[#a3a3a3] hover:text-red-400 hover:bg-white/5 transition-colors" title="Hapus catatan">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="px-6 py-3 border-b border-white/10 flex items-center gap-2 flex-wrap">
                    <span class="px-3 py-1.5 rounded-sm border border-white/10 text-[#a3a3a3] text-xs uppercase tracking-widest">{{ $catLabel }}</span>
                    <div class="w-px h-5 bg-white/10"></div>
                    <span class="px-3 py-1.5 rounded-sm border border-white/10 text-[#a3a3a3] text-xs uppercase tracking-widest inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                        {{ $activeNote['novel_title'] }}
                    </span>
                </div>

                <div class="flex-1 p-6 md:p-10 space-y-8 overflow-y-auto max-h-[640px]">
                    <div class="max-w-[780px] mx-auto space-y-8">
                        @if(!empty($activeNote['tags']))
                            <div class="flex items-center gap-2 flex-wrap">
                                @foreach($activeNote['tags'] as $t)
                                    <span class="px-2.5 py-1 rounded-sm border border-white/10 text-[9px] uppercase tracking-[0.2em] text-[#a3a3a3] bg-[#0a0a0a]">{{ $t }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-10">
                            <h1 class="font-serif text-[38px] md:text-[44px] text-[#f2efe8] leading-[1.1]">{{ $activeNote['title'] }}</h1>
                            <p class="text-[10px] uppercase tracking-[0.22em] text-[#a3a3a3] mt-4 pb-6 border-b border-white/10">{{ $activeNote['updated'] }}</p>
                        </div>

                        @if($activeNote['is_open_question'])
                            <div class="rounded-sm border border-[#c7a64a]/30 bg-[#c7a64a]/5 p-6">
                                <p class="text-[10px] uppercase tracking-[0.25em] text-[#c7a64a] mb-3">OPEN QUESTION</p>
                                @foreach(array_filter(preg_split('/\n\s*\n/', trim($activeNote['content']))) as $para)
                                    <p class="font-serif text-[16px] text-[#e8e3d6] leading-[1.8] italic">{{ $para }}</p>
                                @endforeach
                            </div>
                        @else
                            @foreach(array_filter(preg_split('/\n\s*\n/', trim($activeNote['content']))) as $para)
                                <p class="text-[14px] text-[#d4cfc2] leading-[1.9]">{{ $para }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-white/10 flex items-center justify-between gap-3 flex-wrap">
                    <a @if($selectedNovel) href="{{ route('writer.novels.workspace', $selectedNovel) }}" @endif class="px-2.5 py-1 rounded-sm border border-[#c7a64a]/30 text-[10px] uppercase tracking-[0.15em] text-[#c7a64a] bg-[#c7a64a]/5 inline-flex items-center gap-1.5 hover:bg-[#c7a64a]/10 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                        {{ $activeNote['novel_title'] }}
                    </a>
                    <button type="button" @click="editOpen = ! editOpen" class="px-4 py-2 rounded-sm border border-white/10 text-[#a3a3a3] text-xs uppercase tracking-[0.2em] hover:bg-white/5 hover:text-[#f2efe8] transition-colors inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                        Edit note
                    </button>
                </div>

                <div x-show="editOpen" x-cloak x-transition class="border-t border-white/10 bg-[#0a0a0a] p-6">
                    <form action="{{ route('writer.plot.update', $activeNote['id']) }}" method="POST" class="space-y-4 max-w-[780px] mx-auto">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Title</label>
                            <input type="text" name="title" value="{{ $activeNote['title'] }}" required class="w-full px-4 py-3 text-sm bg-[#121212] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Category</label>
                            <select name="category" class="w-full px-4 py-3 text-sm bg-[#121212] border border-white/10 rounded-sm text-[#f2efe8] focus:outline-none focus:border-[#c7a64a]/40">
                                @foreach(['scene_outline' => 'Scene outline', 'character_arc' => 'Character arc', 'world' => 'World & motifs', 'continuity' => 'Continuity', 'general' => 'Story note'] as $val => $lab)
                                    <option value="{{ $val }}" @selected($activeNote['category'] === $val)>{{ $lab }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Content</label>
                            <textarea name="content" rows="8" class="w-full px-4 py-3 text-sm bg-[#121212] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">{{ $activeNote['content'] }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Tags <span class="normal-case tracking-normal">(pisahkan dengan koma)</span></label>
                            <input type="text" name="tags" value="{{ implode(', ', $activeNote['tags'] ?? []) }}" class="w-full px-4 py-3 text-sm bg-[#121212] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">
                        </div>
                        <label class="inline-flex items-center gap-2 text-xs text-[#a3a3a3]">
                            <input type="checkbox" name="is_open_question" value="1" @checked($activeNote['is_open_question']) class="h-4 w-4 rounded-sm bg-[#121212] border border-white/10 accent-[#c7a64a]">
                            Mark as open question
                        </label>
                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit" class="px-4 py-2 bg-[#c7a64a] text-black text-xs uppercase tracking-[0.15em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors">Save changes</button>
                            <button type="button" @click="editOpen = false" class="px-4 py-2 rounded-sm border border-white/10 text-[#a3a3a3] text-xs uppercase tracking-[0.2em] hover:bg-white/5 hover:text-[#f2efe8] transition-colors">Cancel</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div x-show="showNew" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70" @click="showNew = false"></div>
        <div class="relative w-full max-w-lg rounded-sm border border-white/10 bg-[#121212] shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
                <h3 class="font-serif text-[20px] text-[#f2efe8]">New note</h3>
                <button type="button" @click="showNew = false" class="p-2 rounded-sm text-[#a3a3a3] hover:text-[#f2efe8] hover:bg-white/5 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="{{ route('writer.plot.store') }}" method="POST" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                @csrf
                <input type="hidden" name="novel_id" value="{{ $selectedNovel?->id }}">
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Title</label>
                    <input type="text" name="title" required class="w-full px-4 py-3 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Category</label>
                    <select name="category" class="w-full px-4 py-3 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] focus:outline-none focus:border-[#c7a64a]/40">
                        @foreach(['scene_outline' => 'Scene outline', 'character_arc' => 'Character arc', 'world' => 'World & motifs', 'continuity' => 'Continuity', 'general' => 'Story note'] as $val => $lab)
                            <option value="{{ $val }}">{{ $lab }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Content</label>
                    <textarea name="content" rows="6" class="w-full px-4 py-3 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2">Tags <span class="normal-case tracking-normal">(pisahkan dengan koma)</span></label>
                    <input type="text" name="tags" class="w-full px-4 py-3 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">
                </div>
                <div class="flex items-center gap-4 flex-wrap">
                    <label class="inline-flex items-center gap-2 text-xs text-[#a3a3a3]">
                        <input type="checkbox" name="is_open_question" value="1" class="h-4 w-4 rounded-sm bg-[#0a0a0a] border border-white/10 accent-[#c7a64a]">
                        Open question
                    </label>
                    <label class="inline-flex items-center gap-2 text-xs text-[#a3a3a3]">
                        <input type="checkbox" name="is_pinned" value="1" class="h-4 w-4 rounded-sm bg-[#0a0a0a] border border-white/10 accent-[#c7a64a]">
                        Pin note
                    </label>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="submit" class="px-4 py-2 bg-[#c7a64a] text-black text-xs uppercase tracking-[0.15em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors">Create note</button>
                    <button type="button" @click="showNew = false" class="px-4 py-2 rounded-sm border border-white/10 text-[#a3a3a3] text-xs uppercase tracking-[0.2em] hover:bg-white/5 hover:text-[#f2efe8] transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
