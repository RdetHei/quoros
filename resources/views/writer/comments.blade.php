@extends('layouts.writer', [
    'active' => $active ?? 'comments',
])

@section('dashboard-content')
<div class="space-y-5" x-data="{ q: '', chapterOpen: false }">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2 flex-wrap">
            @foreach($tabs as $t)
                <a href="{{ route('writer.comments', array_filter(['filter' => $t['key'], 'chapter' => $chapterFilter ?: null, 'sort' => $sort === 'asc' ? 'oldest' : null])) }}"
                   @class([
                       'px-4 py-2 rounded-sm border text-xs uppercase tracking-[0.15em] transition-colors',
                       $filter === $t['key'] ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10 text-[#c7a64a]' : 'border-white/10 bg-[#121212] text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]'
                   ])>
                    {{ $t['label'] }} · <span class="ml-0.5">{{ $t['count'] }}</span>
                </a>
            @endforeach
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <div class="relative" @click.outside="chapterOpen = false">
                <button type="button" @click="chapterOpen = !chapterOpen" class="px-4 py-2 rounded-sm border border-white/10 bg-[#121212] text-[#a3a3a3] text-xs uppercase tracking-widest hover:bg-white/5 transition-colors inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    {{ $chapterFilter ? 'Chapter ' . str_pad((string) ($chaptersWithComments->firstWhere('id', $chapterFilter)->order ?? $chapterFilter), 2, '0', STR_PAD_LEFT) : 'All Chapters' }}
                </button>
                <div x-show="chapterOpen" x-cloak x-transition class="absolute right-0 z-30 mt-2 w-64 rounded-sm border border-white/10 bg-[#121212] shadow-xl overflow-hidden">
                    <a href="{{ route('writer.comments', array_filter(['filter' => $filter, 'sort' => $sort === 'asc' ? 'oldest' : null])) }}" class="block px-4 py-2.5 text-[12px] {{ !$chapterFilter ? 'text-[#c7a64a]' : 'text-[#a3a3a3] hover:text-[#f2efe8] hover:bg-white/5' }}">All chapters</a>
                    @foreach($chaptersWithComments as $ch)
                        <a href="{{ route('writer.comments', array_filter(['filter' => $filter, 'chapter' => $ch->id, 'sort' => $sort === 'asc' ? 'oldest' : null])) }}" class="block px-4 py-2.5 text-[12px] truncate border-t border-white/5 {{ $chapterFilter === $ch->id ? 'text-[#c7a64a]' : 'text-[#a3a3a3] hover:text-[#f2efe8] hover:bg-white/5' }}">
                            {{ str_pad((string) ($ch->order ?: $ch->id), 2, '0', STR_PAD_LEFT) }} · {{ $ch->title }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,420px)_minmax(0,1fr)] gap-5">
        <div class="space-y-4">
            <div class="rounded-sm border border-white/10 bg-[#121212] overflow-hidden">
                <div class="p-4 border-b border-white/10">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#a3a3a3] absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <input x-model="q" placeholder="Search reader feedback..." class="w-full pl-11 pr-4 py-3 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40">
                    </div>
                </div>
                <div class="p-4 space-y-4 max-h-[720px] overflow-y-auto">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#c7a64a]">{{ $novels->first()->title ?? 'Your novels' }}</p>
                        <a href="{{ route('writer.comments', array_filter(['filter' => $filter, 'chapter' => $chapterFilter ?: null, 'sort' => $sort === 'asc' ? null : 'oldest'])) }}"
                           class="text-[10px] uppercase tracking-[0.15em] text-[#a3a3a3] hover:text-[#f2efe8] inline-flex items-center gap-1">
                            {{ $sort === 'asc' ? 'OLDEST FIRST' : 'NEWEST FIRST' }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" /></svg>
                        </a>
                    </div>

                    @forelse($conversations as $c)
                        <a href="{{ route('writer.comments', array_filter(['filter' => $filter, 'chapter' => $chapterFilter ?: null, 'sort' => $sort === 'asc' ? 'oldest' : null, 'comment' => $c['id']])) }}"
                           x-show="q === '' || @js($c['name'] . ' ' . $c['text'] . ' ' . $c['chapter']).toLowerCase().includes(q.toLowerCase())"
                           @class([
                               'block p-4 rounded-sm border transition-all group',
                               ($activeThread && $activeThread['comment_id'] === $c['id']) ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10' : 'border-white/10 bg-[#0a0a0a] hover:border-white/20 hover:bg-white/5'
                           ])>
                            <div class="flex items-start gap-3">
                                <div class="relative">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-serif text-[14px] text-[#f2efe8] shrink-0" style="background: {{ $c['color'] }}1f; border: 1px solid {{ $c['color'] }}55;">
                                        {{ $c['initials'] }}
                                    </div>
                                    @if($c['new'])
                                        <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full border-2 border-[#0a0a0a]" style="background: #c7a64a;"></span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <h4 class="text-[15px] text-[#f2efe8] font-medium truncate">{{ $c['name'] }}</h4>
                                        <span class="text-[10px] text-[#a3a3a3] whitespace-nowrap">{{ $c['time'] }}</span>
                                    </div>
                                    <p class="text-[13px] text-[#d4cfc2] leading-relaxed line-clamp-2 mb-2">{{ $c['text'] }}</p>
                                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#a3a3a3]">{{ $c['chapter'] }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="py-10 text-center">
                            <p class="text-[12.5px] text-[#a3a3a3]">No conversations here yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="rounded-sm border border-white/10 bg-[#121212] overflow-hidden flex flex-col">
            @if(!$activeThread)
                <div class="flex-1 flex items-center justify-center py-24 px-6 text-center">
                    <div>
                        <h3 class="font-serif text-[22px] text-[#f2efe8] mb-2">No conversation selected</h3>
                        <p class="text-[12.5px] text-[#a3a3a3]">When readers leave a comment on your chapters, their threads appear here.</p>
                    </div>
                </div>
            @else
                <div class="px-6 py-4 border-b border-white/10 flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.25em] text-[#c7a64a] mb-1.5">{{ $activeThread['chapter'] }}</p>
                        <h2 class="font-serif text-[24px] text-[#f2efe8]">{{ $activeThread['title'] }}</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-sm border {{ $activeThread['status_badge'] === 'NEEDS REPLY' ? 'border-[#c7a64a]/40 text-[#c7a64a]' : 'border-white/10 text-[#a3a3a3]' }} text-[10px] uppercase tracking-[0.22em] bg-[#c7a64a]/5 whitespace-nowrap">{{ $activeThread['status_badge'] }}</span>
                        @if($activeThread['novel_slug'])
                            <a href="{{ route('chapters.show', [$activeThread['novel_slug'], $activeThread['chapter_order']]) }}" target="_blank" class="p-2 rounded-sm border border-white/10 text-[#a3a3a3] hover:text-[#f2efe8] hover:bg-white/5 transition-colors" title="View chapter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex-1 p-6 md:p-8 space-y-8 overflow-y-auto max-h-[620px]">
                    @foreach($activeThread['comments'] as $cmt)
                        <div class="@if($cmt['indented'] ?? false) ml-8 pl-5 border-l border-[#c7a64a]/30 @endif">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-11 h-11 rounded-full flex items-center justify-center font-serif text-[15px] text-[#f2efe8] shrink-0" style="background: {{ $cmt['color'] }}1f; border: 1px solid {{ $cmt['color'] }}55;">
                                    {{ $cmt['initials'] }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-[15px] text-[#f2efe8] font-medium">{{ $cmt['name'] }}</h5>
                                    <p class="text-[10px] uppercase tracking-[0.22em] text-[#a3a3a3] mt-0.5">{{ $cmt['meta'] }}</p>
                                </div>
                            </div>
                            <p class="font-serif text-[16px] text-[#e8e3d6] leading-[1.8] mb-4">{{ $cmt['text'] }}</p>
                            <div class="flex items-center gap-4 text-xs">
                                <span class="inline-flex items-center gap-1.5 text-[#a3a3a3]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="#c7a64a" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                                    <span class="text-[#c7a64a] font-medium">{{ $cmt['likes'] }} LIKES</span>
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <div class="border-t border-white/10 pt-6">
                        <form method="POST" action="{{ route('comments.store', $activeThread['chapter_id']) }}" class="rounded-sm border border-[#c7a64a]/30 bg-[#0a0a0a] overflow-hidden">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $activeThread['comment_id'] }}">
                            <div class="px-5 py-3.5 border-b border-[#c7a64a]/20 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" /></svg>
                                <span class="font-serif text-[15px] text-[#f2efe8]">Reply as {{ auth()->user()->name }}</span>
                                <span class="ml-auto text-[10px] uppercase tracking-[0.22em] text-[#c7a64a] px-2.5 py-0.5 rounded-sm border border-[#c7a64a]/30">AUTHOR</span>
                            </div>
                            <div class="p-5">
                                <textarea name="content" rows="3" required placeholder="Write a thoughtful reply..." class="w-full bg-transparent border-0 text-[15px] text-[#e8e3d6] leading-relaxed placeholder:text-[#a3a3a3]/60 focus:outline-none resize-none font-serif"></textarea>
                                <div class="flex items-center justify-between gap-2 flex-wrap mt-2">
                                    <p class="text-[10px] uppercase tracking-[0.22em] text-[#a3a3a3]">Visible to all readers</p>
                                    <button type="submit" class="px-5 py-2.5 bg-[#c7a64a] text-black text-xs uppercase tracking-[0.2em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors inline-flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                                        Send Reply
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
