@props(['name' => 'content', 'placeholder' => 'Tulis sesuatu…', 'rows' => 3])

@php
    $packs = \App\Support\ChatCatalog::packs();
    $emojis = \App\Support\ChatCatalog::emojiEntries();
@endphp

<div x-data="communityComposer()" class="space-y-2">
    <input type="hidden" name="sticker" :value="sticker">

    <div x-show="stickerPreview" x-cloak class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/[.04] px-3 py-2">
        <template x-if="stickerPreview?.image">
            <img :src="stickerPreview.image" alt="" class="h-9 w-9 object-contain">
        </template>
        <span x-show="!stickerPreview?.image" class="text-2xl" x-text="stickerPreview?.emoji"></span>
        <span class="text-xs text-[#a3a3a3]" x-text="stickerPreview?.label"></span>
        <button type="button" @click="clearSticker()" class="text-[10px] font-bold uppercase tracking-widest text-[#a3a3a3] hover:text-[#f2efe8] transition-colors">Hapus</button>
    </div>

    <textarea x-ref="composer" name="{{ $name }}" rows="{{ $rows }}"
              class="w-full bg-white/[.03] border border-white/10 rounded-xl px-4 py-3.5 text-sm text-[#f2efe8] leading-relaxed focus:outline-none focus:border-[#c7a64a]/60 focus:bg-white/[.05] transition-colors placeholder-[#7a7a7a] resize-none"
              placeholder="{{ $placeholder }}">{{ old($name) }}</textarea>

    <div class="flex items-center gap-2">
        <div class="relative">
            <button type="button" @click="showEmoji = !showEmoji; showStickers = false"
                    class="h-9 px-3 rounded-lg border border-white/10 bg-white/[.03] text-xs font-bold uppercase tracking-widest text-[#a3a3a3] hover:text-[#f2efe8] hover:border-white/20 transition-colors">
                Emoji
            </button>
            <div x-show="showEmoji" @click.away="showEmoji = false" x-cloak
                 class="absolute bottom-11 left-0 z-20 w-64 rounded-xl border border-white/10 bg-[#161616] p-2 grid grid-cols-8 gap-1 shadow-2xl">
                @forelse($emojis as $entry)
                    <button type="button" class="h-8 rounded-md hover:bg-white/10 transition-colors inline-flex items-center justify-center" @click="insertEmoji(@js($entry))">
                        @if(! empty($entry['image']))
                            <img src="{{ $entry['image'] }}" alt="" class="h-5 w-5 object-contain">
                        @else
                            {{ $entry['emoji'] }}
                        @endif
                    </button>
                @empty
                    <p class="col-span-8 px-1 py-2 text-center text-[10px] uppercase tracking-widest text-[#7a7a7a]">Belum ada emoji</p>
                @endforelse
            </div>
        </div>
        <div class="relative">
            <button type="button" @click="showStickers = !showStickers; showEmoji = false"
                    class="h-9 px-3 rounded-lg border border-white/10 bg-white/[.03] text-xs font-bold uppercase tracking-widest text-[#a3a3a3] hover:text-[#f2efe8] hover:border-white/20 transition-colors">
                Stiker
            </button>
            <div x-show="showStickers" @click.away="showStickers = false" x-cloak
                 class="absolute bottom-11 left-0 z-20 w-72 rounded-xl border border-white/10 bg-[#161616] p-3 shadow-2xl">
                <div class="flex flex-wrap gap-1 mb-2">
                    @foreach($packs as $index => $pack)
                        <button type="button" @click="stickerPack = {{ $index }}"
                                class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest transition-colors"
                                :class="stickerPack === {{ $index }} ? 'bg-[#c7a64a] text-[#141414]' : 'text-[#a3a3a3] hover:text-[#f2efe8]'">
                            {{ $pack['label'] }}
                        </button>
                    @endforeach
                </div>
                @forelse($packs as $index => $pack)
                    <div x-show="stickerPack === {{ $index }}" class="grid grid-cols-4 gap-2">
                        @foreach($pack['items'] as $item)
                            <button type="button" @click='pickSticker(@json($item))'
                                    class="rounded-lg border border-white/10 bg-white/[.02] p-2 hover:border-[#c7a64a]/50 hover:bg-white/[.05] transition-colors">
                                @if(! empty($item['image']))
                                    <img src="{{ $item['image'] }}" alt="" class="h-8 w-8 mx-auto object-contain" loading="lazy">
                                @else
                                    <span class="block text-2xl text-center">{{ $item['emoji'] }}</span>
                                @endif
                                <span class="block text-[9px] text-center text-[#a3a3a3] truncate">{{ $item['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @empty
                    <p class="px-1 py-2 text-center text-[10px] uppercase tracking-widest text-[#7a7a7a]">Belum ada stiker</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
