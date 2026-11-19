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
     @class(['flex gap-3 md:gap-4 group', 'ml-8 md:ml-12 border-l-2 border-slate-100 dark:border-slate-800 pl-4' => $isReply])>
    @if($comment->user)
        <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}"
           class="w-8 h-8 md:w-10 md:h-10 flex-shrink-0 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold text-xs md:text-sm hover:ring-2 hover:ring-slate-500/40 transition-all">
            @if($comment->user->profile_photo_url)
                <img src="{{ $comment->user->profile_photo_url }}" alt="" class="w-full h-full object-cover rounded-full" onerror="this.onerror=null; this.src='/error.png'">
            @elseif($comment->user->profile_photo)
                <img src="{{ asset('storage/' . $comment->user->profile_photo) }}" alt="" class="w-full h-full object-cover rounded-full" onerror="this.onerror=null; this.src='/error.png'">
            @else
                {{ substr($comment->user->name, 0, 1) }}
            @endif
        </a>
    @else
        <div class="w-8 h-8 md:w-10 md:h-10 flex-shrink-0 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 font-bold text-xs">?</div>
    @endif
    <div class="flex-grow min-w-0">
        <div class="flex items-center justify-between mb-1 gap-2">
            @if($comment->user)
                <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}" class="font-bold text-xs md:text-sm text-slate-900 dark:text-white truncate">{{ $comment->user->name }}</a>
            @else
                <span class="font-bold text-xs md:text-sm text-slate-400 italic">Deleted User</span>
            @endif
            <span class="text-[9px] md:text-[10px] font-medium text-slate-400 uppercase tracking-widest shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
        </div>

        @if($sticker)
            <div class="mb-2 inline-flex flex-col items-center rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2">
                <span class="text-4xl leading-none">{{ $sticker['emoji'] }}</span>
                <span class="mt-1 text-[9px] uppercase tracking-widest text-slate-400">{{ $sticker['label'] }}</span>
            </div>
        @endif

        @if(filled($comment->content))
            <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-wrap">{{ $comment->content }}</p>
        @endif

        <div class="mt-3 flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 p-0.5">
                @auth
                    <button type="button" @click="send({ reaction_type: 'like' })"
                            class="h-7 w-7 rounded-md text-[11px] text-slate-400 hover:text-amber-500"
                            :class="userVote === 'like' && 'text-amber-500 bg-amber-500/10'"
                            title="Upvote">▲</button>
                @endauth
                <span class="min-w-[1.4rem] text-center text-[10px] font-bold tabular-nums text-slate-500"
                      :class="score > 0 ? 'text-amber-600 dark:text-amber-400' : (score < 0 ? 'text-rose-500' : '')"
                      x-text="score">{{ $score }}</span>
                @auth
                    <button type="button" @click="send({ reaction_type: 'dislike' })"
                            class="h-7 w-7 rounded-md text-[11px] text-slate-400 hover:text-rose-500"
                            :class="userVote === 'dislike' && 'text-rose-500 bg-rose-500/10'"
                            title="Downvote">▼</button>
                @endauth
            </div>

            <template x-for="chip in emojis" :key="chip.emoji">
                <button type="button" @click="send({ emoji: chip.emoji })"
                        class="h-7 px-2 rounded-full border text-[12px] inline-flex items-center gap-1"
                        :class="chip.mine ? 'border-amber-400/50 bg-amber-400/10' : 'border-slate-200 dark:border-slate-700'">
                    <span x-text="chip.emoji"></span>
                    <span class="text-[10px] font-bold text-slate-500" x-text="chip.count"></span>
                </button>
            </template>

            @auth
                <div class="relative">
                    <button type="button" @click="emojiOpen = !emojiOpen" class="h-7 px-2 rounded-full border border-slate-200 dark:border-slate-700 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Emoji
                    </button>
                    <div x-show="emojiOpen" @click.away="emojiOpen = false" x-cloak
                         class="absolute z-20 mt-2 w-56 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-2 grid grid-cols-8 gap-1 shadow-xl">
                        @foreach(\App\Support\ChatCatalog::emojis() as $emoji)
                            <button type="button" class="h-7 rounded hover:bg-slate-100 dark:hover:bg-slate-800" @click="send({ emoji: @js($emoji) }); emojiOpen = false">{{ $emoji }}</button>
                        @endforeach
                    </div>
                </div>
            @endauth

            @can('delete', $comment)
                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-slate-400 hover:text-red-500 text-[9px] font-bold uppercase tracking-widest" onclick="return confirm('Hapus komentar ini?')">Hapus</button>
                </form>
            @endcan

            @auth
                @if(! $isReply && $comment->user)
                    <button type="button"
                            @click="$dispatch('open-reply', { parentId: {{ $comment->id }}, name: @js($comment->user->name) })"
                            class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-indigo-500 transition-colors">
                        Reply
                    </button>
                @endif
                @if($comment->user && $comment->user_id !== Auth::id())
                    @include('partials.report-trigger', [
                        'type' => 'comment',
                        'id' => $comment->id,
                        'label' => 'Comment by '.$comment->user->name,
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
