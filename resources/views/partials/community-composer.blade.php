@props(['name' => 'content', 'placeholder' => 'Tulis sesuatu…', 'rows' => 3])

@php
    $packs = \App\Support\ChatCatalog::packs();
    $emojis = \App\Support\ChatCatalog::emojis();
@endphp

<div x-data="communityComposer()" class="space-y-2">
    <input type="hidden" name="sticker" :value="sticker">

    <div x-show="stickerPreview" x-cloak class="inline-flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2">
        <span class="text-2xl" x-text="stickerPreview?.emoji"></span>
        <span class="text-xs text-slate-500" x-text="stickerPreview?.label"></span>
        <button type="button" @click="clearSticker()" class="text-[10px] uppercase tracking-widest text-slate-400">Hapus</button>
    </div>

    <textarea x-ref="composer" name="{{ $name }}" rows="{{ $rows }}"
              class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all placeholder-slate-400 resize-none"
              placeholder="{{ $placeholder }}">{{ old($name) }}</textarea>

    <div class="flex items-center gap-2">
        <div class="relative">
            <button type="button" @click="showEmoji = !showEmoji; showStickers = false"
                    class="h-9 px-3 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-slate-900 dark:hover:text-white">
                Emoji
            </button>
            <div x-show="showEmoji" @click.away="showEmoji = false" x-cloak
                 class="absolute bottom-11 left-0 z-20 w-64 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-2 grid grid-cols-8 gap-1 shadow-xl">
                @foreach($emojis as $emoji)
                    <button type="button" class="h-8 rounded hover:bg-slate-100 dark:hover:bg-slate-800" @click="insertEmoji(@js($emoji))">{{ $emoji }}</button>
                @endforeach
            </div>
        </div>
        <div class="relative">
            <button type="button" @click="showStickers = !showStickers; showEmoji = false"
                    class="h-9 px-3 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-slate-900 dark:hover:text-white">
                Stiker
            </button>
            <div x-show="showStickers" @click.away="showStickers = false" x-cloak
                 class="absolute bottom-11 left-0 z-20 w-72 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-3 shadow-xl">
                <div class="flex flex-wrap gap-1 mb-2">
                    @foreach($packs as $index => $pack)
                        <button type="button" @click="stickerPack = {{ $index }}"
                                class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest"
                                :class="stickerPack === {{ $index }} ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-500'">
                            {{ $pack['label'] }}
                        </button>
                    @endforeach
                </div>
                @foreach($packs as $index => $pack)
                    <div x-show="stickerPack === {{ $index }}" class="grid grid-cols-4 gap-2">
                        @foreach($pack['items'] as $item)
                            <button type="button" @click='pickSticker(@json($item))'
                                    class="rounded-xl border border-slate-200 dark:border-slate-700 p-2 hover:border-indigo-400">
                                <span class="block text-2xl text-center">{{ $item['emoji'] }}</span>
                                <span class="block text-[9px] text-center text-slate-400 truncate">{{ $item['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
