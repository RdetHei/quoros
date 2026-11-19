@extends('layouts.app', [
    'title' => 'Lounge · Chat Publik · Quoros',
])

@push('styles')
<style>
    .chat-page {
        --gold: #c7a64a;
        --ink: #f2efe8;
        --muted: #a3a3a3;
        min-height: calc(100vh - 180px);
    }
    .chat-shell {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 1.25rem;
    }
    @media (min-width: 1024px) {
        .chat-shell { grid-template-columns: 280px minmax(0, 1fr); }
    }
    .chat-panel {
        background: #121212;
        border: 1px solid rgba(212, 175, 55, 0.14);
        border-radius: 1.25rem;
        overflow: hidden;
    }
    .chat-scroller {
        height: min(68vh, 720px);
        overflow-y: auto;
        padding: 1.25rem;
    }
    .chat-scroller::-webkit-scrollbar { width: 8px; }
    .chat-scroller::-webkit-scrollbar-thumb { background: rgba(199, 166, 74, 0.35); border-radius: 99px; }
    .vote-btn.active-up { color: #c7a64a; background: rgba(199, 166, 74, 0.12); }
    .vote-btn.active-down { color: #fb7185; background: rgba(244, 63, 94, 0.12); }
</style>
@endpush

@section('content')
<div class="chat-page"
     x-data="chatLounge({
        messages: @js($messages),
        packs: @js($packs),
        emojis: @js($emojis),
        feedUrl: @js(route('chat.feed')),
        storeUrl: @js(route('chat.store')),
        reactionUrl: @js(url('/reactions/chat/__ID__')),
        destroyUrlTemplate: @js(url('/chat/messages/__ID__')),
        loginUrl: @js(route('login')),
        canPost: @js(Auth::check()),
     })">
    <div class="mb-8">
        <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#c7a64a]">Komunitas</p>
        <h1 class="mt-2 font-serif text-4xl sm:text-5xl font-semibold uppercase tracking-wide text-[#f2efe8]">Lounge Publik</h1>
        <p class="mt-3 max-w-xl text-sm text-[#a3a3a3] leading-relaxed">
            Ruang obrolan terbuka untuk pembaca dan penulis. Kirim stiker, beri emoji, dan upvote percakapan yang menarik.
        </p>
    </div>

    <div class="chat-shell">
        <aside class="chat-panel p-5 h-fit">
            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#c7a64a]">Saluran</p>
            <div class="mt-3 rounded-xl border border-[#c7a64a]/20 bg-[#c7a64a]/10 px-4 py-3">
                <p class="text-sm font-semibold text-[#f2efe8]"># umum</p>
                <p class="mt-1 text-xs text-[#a3a3a3]">Chat publik seluruh Quoros</p>
            </div>
            <ul class="mt-5 space-y-2 text-xs text-[#a3a3a3] leading-relaxed">
                <li>Vote naik untuk sorot pesan bagus.</li>
                <li>Stiker dan emoji untuk reaksi cepat.</li>
                <li>Hormati penulis lain — jangan spoil bab terbaru.</li>
            </ul>
        </aside>

        <section class="chat-panel flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                <div>
                    <p class="text-sm font-semibold text-[#f2efe8]">Obrolan langsung</p>
                    <p class="text-[11px] text-[#a3a3a3]"><span x-text="messages.length"></span> pesan di layar</p>
                </div>
                <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,.8)]" title="Live"></span>
            </div>

            <div x-ref="scroller" class="chat-scroller space-y-4" @scroll="onScroll($event)">
                <template x-if="messages.length === 0">
                    <div class="py-16 text-center text-sm text-[#a3a3a3]">
                        Lounge masih sepi. Jadilah yang pertama menyapa.
                    </div>
                </template>

                <template x-for="message in messages" :key="message.id">
                    <article class="flex gap-3 group">
                        <a :href="message.user?.profile_url || '#'"
                           class="h-9 w-9 shrink-0 rounded-full bg-[#1a1a1a] ring-1 ring-[#c7a64a]/20 overflow-hidden flex items-center justify-center text-xs font-bold text-[#c7a64a]">
                            <img x-show="message.user?.avatar" :src="message.user?.avatar" alt="" class="h-full w-full object-cover" x-on:error="$el.remove()">
                            <span x-show="!message.user?.avatar" x-text="message.user?.initial || '?'"></span>
                        </a>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-baseline gap-2">
                                <a :href="message.user?.profile_url || '#'" class="text-sm font-semibold text-[#f2efe8]" x-text="message.user?.name || 'Pengguna'"></a>
                                <span class="text-[10px] uppercase tracking-widest text-[#737373]" x-text="message.created_human"></span>
                            </div>
                            <p x-show="message.parent" class="mt-1 text-[11px] text-[#c7a64a]/80 truncate">
                                Membalas <span x-text="message.parent?.name"></span>: <span x-text="message.parent?.excerpt"></span>
                            </p>
                            <p x-show="message.body" class="mt-1 text-sm text-[#d9d4c8] whitespace-pre-wrap break-words" x-text="message.body"></p>
                            <div x-show="message.sticker" class="mt-2 inline-flex flex-col items-center rounded-2xl border border-[#c7a64a]/15 bg-white/5 px-4 py-3">
                                <span class="text-5xl leading-none" x-text="message.sticker?.emoji"></span>
                                <span class="mt-1 text-[10px] uppercase tracking-widest text-[#a3a3a3]" x-text="message.sticker?.label"></span>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                <div class="inline-flex items-center rounded-lg border border-white/10 bg-black/20 p-0.5">
                                    <button type="button" @click="vote(message, 'like')"
                                            class="vote-btn h-7 w-7 rounded-md text-[#a3a3a3] hover:text-[#c7a64a]"
                                            :class="message.user_vote === 'like' && 'active-up'"
                                            title="Upvote">▲</button>
                                    <span class="min-w-[1.5rem] text-center text-[11px] font-bold tabular-nums"
                                          :class="message.score > 0 ? 'text-[#c7a64a]' : (message.score < 0 ? 'text-rose-400' : 'text-[#a3a3a3]')"
                                          x-text="message.score"></span>
                                    <button type="button" @click="vote(message, 'dislike')"
                                            class="vote-btn h-7 w-7 rounded-md text-[#a3a3a3] hover:text-rose-400"
                                            :class="message.user_vote === 'dislike' && 'active-down'"
                                            title="Downvote">▼</button>
                                </div>

                                <template x-for="chip in message.emojis" :key="chip.emoji">
                                    <button type="button" @click="react(message, chip.emoji)"
                                            class="h-7 px-2 rounded-full border text-[12px] inline-flex items-center gap-1"
                                            :class="chip.mine ? 'border-[#c7a64a]/40 bg-[#c7a64a]/15' : 'border-white/10 bg-white/5'">
                                        <span x-text="chip.emoji"></span>
                                        <span class="text-[10px] font-bold text-[#a3a3a3]" x-text="chip.count"></span>
                                    </button>
                                </template>

                                <div class="relative">
                                    <button type="button" @click="message._emojiOpen = !message._emojiOpen"
                                            class="h-7 px-2 rounded-full border border-white/10 text-[11px] text-[#a3a3a3] hover:text-[#f2efe8]">
                                        + emoji
                                    </button>
                                    <div x-show="message._emojiOpen" @click.away="message._emojiOpen = false" x-cloak
                                         class="absolute z-20 mt-2 w-56 rounded-xl border border-[#c7a64a]/20 bg-[#161616] p-2 grid grid-cols-8 gap-1 shadow-xl">
                                        <template x-for="emoji in emojis" :key="emoji">
                                            <button type="button" class="h-7 rounded hover:bg-white/10" x-text="emoji"
                                                    @click="react(message, emoji); message._emojiOpen = false"></button>
                                        </template>
                                    </div>
                                </div>

                                <button type="button" @click="setReply(message)" class="h-7 px-2 text-[10px] font-bold uppercase tracking-widest text-[#a3a3a3] hover:text-[#c7a64a]">Reply</button>
                                @auth
                                <button type="button" x-show="message.can_delete" @click="remove(message)" class="h-7 px-2 text-[10px] font-bold uppercase tracking-widest text-rose-400/80 hover:text-rose-400">Hapus</button>
                                <button type="button"
                                        x-show="message.user && message.user.id !== {{ (int) Auth::id() }}"
                                        @click="$dispatch('open-report', { type: 'chat', id: message.id, label: 'Pesan dari ' + (message.user?.name || '') })"
                                        class="h-7 px-2 text-[10px] font-bold uppercase tracking-widest text-[#737373] hover:text-rose-400">Laporkan</button>
                                @endauth
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            <div class="border-t border-white/5 p-4">
                <p x-show="error" class="mb-2 text-xs text-rose-400" x-text="error"></p>
                <div x-show="parent" class="mb-2 flex items-center justify-between rounded-lg bg-[#c7a64a]/10 px-3 py-2 text-xs text-[#e8d39a]">
                    <span>Membalas <span class="font-semibold" x-text="parent?.name"></span></span>
                    <button type="button" @click="parent = null" class="text-[#a3a3a3] hover:text-white">Batal</button>
                </div>
                <div x-show="sticker" class="mb-2 inline-flex items-center gap-2 rounded-xl border border-[#c7a64a]/20 bg-white/5 px-3 py-2">
                    <span class="text-2xl" x-text="sticker?.emoji"></span>
                    <span class="text-xs text-[#a3a3a3]" x-text="sticker?.label"></span>
                    <button type="button" @click="sticker = null" class="text-[10px] uppercase tracking-widest text-[#737373]">Hapus</button>
                </div>

                @auth
                    <div class="flex items-end gap-2">
                        <div class="relative">
                            <button type="button" @click="showEmoji = !showEmoji; showStickers = false"
                                    class="h-11 w-11 rounded-xl border border-white/10 text-lg hover:bg-white/5">😊</button>
                            <div x-show="showEmoji" @click.away="showEmoji = false" x-cloak
                                 class="absolute bottom-12 left-0 z-20 w-64 rounded-xl border border-[#c7a64a]/20 bg-[#161616] p-2 grid grid-cols-8 gap-1 shadow-2xl">
                                <template x-for="emoji in emojis" :key="emoji">
                                    <button type="button" class="h-8 rounded hover:bg-white/10" x-text="emoji" @click="insertEmoji(emoji)"></button>
                                </template>
                            </div>
                        </div>
                        <div class="relative">
                            <button type="button" @click="showStickers = !showStickers; showEmoji = false"
                                    class="h-11 w-11 rounded-xl border border-white/10 text-lg hover:bg-white/5">🎭</button>
                            <div x-show="showStickers" @click.away="showStickers = false" x-cloak
                                 class="absolute bottom-12 left-0 z-20 w-72 rounded-xl border border-[#c7a64a]/20 bg-[#161616] p-3 shadow-2xl">
                                <div class="flex gap-1 mb-2">
                                    <template x-for="(pack, index) in packs" :key="pack.id">
                                        <button type="button" @click="stickerPack = index"
                                                class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest"
                                                :class="stickerPack === index ? 'bg-[#c7a64a] text-[#111]' : 'text-[#a3a3a3] hover:bg-white/5'"
                                                x-text="pack.label"></button>
                                    </template>
                                </div>
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="item in (packs[stickerPack]?.items || [])" :key="item.id">
                                        <button type="button" @click="pickSticker(item)"
                                                class="rounded-xl border border-white/10 bg-white/5 p-2 hover:border-[#c7a64a]/40">
                                            <span class="block text-2xl text-center" x-text="item.emoji"></span>
                                            <span class="block text-[9px] text-center text-[#a3a3a3] truncate" x-text="item.label"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <textarea x-ref="composer" x-model="body" rows="1"
                                  @keydown.enter.prevent="if (!$event.shiftKey) send()"
                                  class="flex-1 resize-none rounded-xl border border-white/10 bg-[#0c0c0c] px-4 py-3 text-sm text-[#f2efe8] placeholder:text-[#737373] focus:outline-none focus:ring-2 focus:ring-[#c7a64a]/40"
                                  placeholder="Tulis di lounge…"></textarea>
                        <button type="button" @click="send()" :disabled="sending"
                                class="h-11 px-4 rounded-xl bg-[#c7a64a] text-[#111] text-[11px] font-bold uppercase tracking-widest disabled:opacity-50">
                            Kirim
                        </button>
                    </div>
                @else
                    <div class="rounded-xl border border-white/10 bg-white/5 p-4 text-center">
                        <p class="text-sm text-[#a3a3a3] mb-3">Masuk untuk ikut ngobrol, vote, dan kirim stiker.</p>
                        <a href="{{ route('login') }}" class="inline-flex h-10 items-center px-5 rounded-xl bg-[#c7a64a] text-[#111] text-[11px] font-bold uppercase tracking-widest">Masuk</a>
                    </div>
                @endauth
            </div>
        </section>
    </div>
</div>
@endsection
