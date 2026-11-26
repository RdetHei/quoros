@props(['comment', 'chapter', 'isReply' => false])

@php
    $score = $comment->voteScore();
    $userVote = $comment->currentUserVote(Auth::id());
    $sticker = $comment->stickerMeta();
    $emojis = $comment->groupedEmojis(Auth::id());
    $reactionUrl = route('reactions.toggle', ['type' => 'comment', 'id' => $comment->id]);
@endphp

<div id="comment-{{ $comment->id }}"
     x-data="{
        score: {{ (int) $score }},
        userVote: @js($userVote),
        emojis: @js($emojis),
        emojiOpen: false,
        async send(payload) {
            const token = document.querySelector('meta[name=csrf-token]')?.content;
            const res = await fetch(@js($reactionUrl), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify(payload),
            });
            if (!res.ok) return;
            const data = await res.json();
            this.score = data.score;
            this.userVote = data.user_vote;
            this.emojis = data.emojis;
        }
     }"
     @class(['flex gap-3 md:gap-4 group', 'ml-8 md:ml-12 border-l-2 border-white/10 pl-4' => $isReply])>
    @if($comment->user)
        <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}"
           class="w-8 h-8 md:w-10 md:h-10 flex-shrink-0 rounded-full bg-white/5 ring-1 ring-white/10 flex items-center justify-center text-[#a3a3a3] font-bold text-xs md:text-sm hover:ring-[#c7a64a]/50 transition-all">
            @if($comment->user->profile_photo_url)
                <img src="{{ $comment->user->profile_photo_url }}" alt="" class="w-full h-full object-cover rounded-full" onerror="this.onerror=null; this.src='/error.png'">
            @elseif($comment->user->profile_photo)
                <img src="{{ asset('storage/' . $comment->user->profile_photo) }}" alt="" class="w-full h-full object-cover rounded-full" onerror="this.onerror=null; this.src='/error.png'">
            @else
                {{ substr($comment->user->name, 0, 1) }}
            @endif
        </a>
    @else
        <div class="w-8 h-8 md:w-10 md:h-10 flex-shrink-0 rounded-full bg-white/5 ring-1 ring-white/10 flex items-center justify-center text-[#a3a3a3] font-bold text-xs">?</div>
    @endif
    <div class="flex-grow min-w-0">
        <div class="flex items-center justify-between mb-1 gap-2">
            @if($comment->user)
                <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}" class="font-bold text-xs md:text-sm text-[#f2efe8] truncate hover:text-[#c7a64a] transition-colors">{{ $comment->user->name }}</a>
            @else
                <span class="font-bold text-xs md:text-sm text-[#7a7a7a] italic">Deleted User</span>
            @endif
            <span class="text-[9px] md:text-[10px] font-medium text-[#7a7a7a] uppercase tracking-widest shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
        </div>

        @if($sticker)
            <div class="mb-2 inline-flex flex-col items-center rounded-xl border border-white/10 bg-white/[.04] px-3 py-2">
                @if(! empty($sticker['image']))
                    <img src="{{ $sticker['image'] }}" alt="{{ $sticker['label'] }}" class="h-16 w-16 object-contain" loading="lazy">
                @else
                    <span class="text-4xl leading-none">{{ $sticker['emoji'] }}</span>
                @endif
                <span class="mt-1 text-[9px] uppercase tracking-widest text-[#a3a3a3]">{{ $sticker['label'] }}</span>
            </div>
        @endif

        @if(filled($comment->content))
            <p class="text-xs md:text-sm text-[#d6d3cc] leading-relaxed whitespace-pre-wrap">{!! \App\Support\ChatCatalog::renderText($comment->content) !!}</p>
        @endif

        <div class="mt-3 flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center rounded-lg bg-white/[.04] border border-white/10 p-0.5">
                @auth
                    <button type="button" @click="send({ reaction_type: 'like' })"
                            class="h-7 w-7 rounded-md text-[11px] text-[#a3a3a3] hover:text-[#c7a64a] transition-colors"
                            :class="userVote === 'like' && 'text-[#c7a64a] bg-[#c7a64a]/10'"
                            title="Upvote">▲</button>
                @endauth
                <span class="min-w-[1.4rem] text-center text-[10px] font-bold tabular-nums text-[#a3a3a3]"
                      :class="score > 0 ? 'text-[#c7a64a]' : (score < 0 ? 'text-rose-400' : '')"
                      x-text="score">{{ $score }}</span>
                @auth
                    <button type="button" @click="send({ reaction_type: 'dislike' })"
                            class="h-7 w-7 rounded-md text-[11px] text-[#a3a3a3] hover:text-rose-400 transition-colors"
                            :class="userVote === 'dislike' && 'text-rose-400 bg-rose-400/10'"
                            title="Downvote">▼</button>
                @endauth
            </div>

            <template x-for="chip in emojis" :key="chip.value">
                <button type="button" @click="send({ emoji: chip.value })"
                        class="h-7 px-2 rounded-full border text-[12px] inline-flex items-center gap-1 transition-colors"
                        :class="chip.mine ? 'border-[#c7a64a]/50 bg-[#c7a64a]/10' : 'border-white/10 bg-white/[.03] hover:border-white/20'">
                    <template x-if="chip.image">
                        <img :src="chip.image" alt="" class="h-4 w-4 object-contain">
                    </template>
                    <span x-show="!chip.image" x-text="chip.emoji"></span>
                    <span class="text-[10px] font-bold text-[#a3a3a3]" x-text="chip.count"></span>
                </button>
            </template>

            @auth
                <div class="relative">
                    <button type="button" @click="emojiOpen = !emojiOpen" class="h-7 px-2 rounded-full border border-white/10 bg-white/[.03] text-[10px] font-bold uppercase tracking-widest text-[#a3a3a3] hover:text-[#f2efe8] hover:border-white/20 transition-colors">
                        Emoji
                    </button>
                    <div x-show="emojiOpen" @click.away="emojiOpen = false" x-cloak
                         class="absolute z-20 mt-2 w-56 rounded-xl border border-white/10 bg-[#161616] p-2 grid grid-cols-8 gap-1 shadow-2xl">
                        @foreach(\App\Support\ChatCatalog::emojiEntries() as $entry)
                            <button type="button" class="h-7 rounded-md hover:bg-white/10 transition-colors inline-flex items-center justify-center" @click="send({ emoji: @js($entry['value']) }); emojiOpen = false">
                                @if(! empty($entry['image']))
                                    <img src="{{ $entry['image'] }}" alt="" class="h-5 w-5 object-contain">
                                @else
                                    {{ $entry['emoji'] }}
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endauth

            @can('delete', $comment)
                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-[#7a7a7a] hover:text-rose-400 text-[9px] font-bold uppercase tracking-widest transition-colors" onclick="return confirm('Hapus komentar ini?')">Hapus</button>
                </form>
            @endcan

            @auth
                @if(! $isReply && $comment->user)
                    <button type="button"
                            @click="$dispatch('open-reply', { parentId: {{ $comment->id }}, name: @js($comment->user->name) })"
                            class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-[#7a7a7a] hover:text-[#c7a64a] transition-colors">
                        Reply
                    </button>
                @endif
                @if($comment->user && $comment->user_id !== Auth::id())
                    @include('partials.report-trigger', [
                        'type' => 'comment',
                        'id' => $comment->id,
                        'label' => 'Comment by '.$comment->user->name,
                        'class' => 'text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-[#7a7a7a] hover:text-rose-400 transition-colors',
                    ])
                @endif
            @endauth
        </div>

        @if(! $isReply)
            @foreach($comment->replies as $reply)
                @include('partials.comment-item', ['comment' => $reply, 'chapter' => $chapter, 'isReply' => true])
            @endforeach
        @endif
    </div>
</div>
