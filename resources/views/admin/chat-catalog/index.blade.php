@extends('layouts.admin')

@php
    $adminTitle = 'Emoji & Stiker';
    $adminBreadcrumbs = ['Admin', 'Community', 'Emoji & Stiker'];
@endphp

@push('styles')
<style>
    .cat-row {display:flex;flex-wrap:wrap;align-items:center;gap:8px;border:1px solid #202022;border-radius:6px;background:#0f0f10;padding:8px 10px;}
    .cat-row + .cat-row {margin-top:6px;}
    .cat-row .console-input {width:auto;}
    .cat-emoji-input {width:70px!important;text-align:center;font-size:18px;line-height:1.2;}
    .cat-label-input {flex:1 1 180px;min-width:150px;}
    .cat-key-input {width:140px!important;}
    .cat-code-input {width:130px!important;}
    .cat-sort-input {width:82px!important;}
    .cat-file {width:100%;max-width:190px;color:#8b887f;font:10px 'DM Mono',monospace;}
    .cat-file::file-selector-button {background:#1a1a1c;color:#d8d3c8;border:1px solid #2b2b2e;border-radius:4px;padding:4px 8px;margin-right:8px;cursor:pointer;font:10px 'DM Mono',monospace;text-transform:uppercase;letter-spacing:.06em;}
    .cat-thumb {width:38px;height:38px;flex:none;border:1px solid #2b2b2e;border-radius:6px;background:#141416;display:flex;align-items:center;justify-content:center;overflow:hidden;font-size:20px;line-height:1;}
    .cat-thumb img {width:100%;height:100%;object-fit:contain;}
    .cat-meta {display:flex;flex-direction:column;gap:2px;min-width:120px;}
    .cat-meta small {color:#77746e;font:9px 'DM Mono',monospace;text-transform:uppercase;letter-spacing:.08em;}
    .cat-check {display:inline-flex;align-items:center;gap:7px;color:#a9a59d;font:10px 'DM Mono',monospace;text-transform:uppercase;letter-spacing:.06em;cursor:pointer;white-space:nowrap;}
    .cat-check input {accent-color:#c6a541;}
    .cat-pack-title {color:#e5e0d7;font:500 17px 'Playfair Display',serif;}
    .cat-empty {color:#77746e;font:12px 'DM Mono',monospace;padding:8px 2px;}
</style>
@endpush

@section('content')
<div class="admin-console mx-auto max-w-[1400px] space-y-5" x-data="{ tab: 'emoji' }">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="admin-kicker">Community / chat catalog</p>
            <h2 class="admin-display mt-1">Emoji &amp; Stiker</h2>
            <p class="mt-2 text-sm text-slate-400">Kelola emoji reaksi dan paket stiker yang tersedia di komentar, chat, dan review. Emoji &amp; stiker bisa berupa gambar upload (dikonversi otomatis ke WebP/AVIF oleh Cloudinary) atau emoji unicode.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" class="console-chip" :class="tab === 'emoji' ? 'is-active' : ''" @click="tab = 'emoji'">Emoji ({{ $emojis->count() }})</button>
            <button type="button" class="console-chip" :class="tab === 'sticker' ? 'is-active' : ''" @click="tab = 'sticker'">Stiker ({{ $packs->count() }} paket)</button>
        </div>
    </div>

    {{-- ══════════ EMOJI ══════════ --}}
    <section x-show="tab === 'emoji'" x-cloak class="space-y-5">
        <div class="console-card p-4">
            <p class="admin-kicker mb-3">Tambah emoji</p>
            <form action="{{ route('admin.chat-catalog.emojis.store') }}" method="POST" enctype="multipart/form-data" class="cat-row">
                @csrf
                <input type="text" name="emoji" value="{{ old('emoji') }}" placeholder="😀" class="console-input cat-emoji-input">
                <input type="text" name="shortcode" value="{{ old('shortcode') }}" placeholder="shortcode" class="console-input cat-code-input">
                <input type="text" name="label" value="{{ old('label') }}" placeholder="Label (opsional)" class="console-input cat-label-input">
                <input type="file" name="image" accept="image/*" class="cat-file">
                <input type="number" name="sort_order" min="0" placeholder="Urutan" class="console-input cat-sort-input">
                <button type="submit" class="console-btn sm">＋ Tambah</button>
            </form>
            <p class="mt-2 text-[11px] text-slate-500">Isi emoji unicode dan/atau unggah gambar. Shortcode (mis. <span class="text-slate-300">fire</span>) dipakai untuk menyisipkan gambar ke teks dan disimpan sebagai reaksi.</p>
            @error('emoji')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
            @error('shortcode')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
            @error('image')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
        </div>

        <div class="console-card p-4">
            <p class="admin-kicker mb-3">Daftar emoji</p>
            <div>
                @forelse($emojis as $emoji)
                    <div class="cat-row">
                        <span class="cat-thumb">
                            @if($emoji->image_url)
                                <img src="{{ $emoji->image_url }}" alt="{{ $emoji->label ?? $emoji->value() }}">
                            @else
                                {{ $emoji->emoji }}
                            @endif
                        </span>
                        <form action="{{ route('admin.chat-catalog.emojis.update', $emoji) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2 flex-grow">
                            @csrf
                            @method('PUT')
                            <input type="text" name="emoji" value="{{ $emoji->emoji }}" placeholder="😀" class="console-input cat-emoji-input">
                            <input type="text" name="shortcode" value="{{ $emoji->shortcode }}" placeholder="shortcode" class="console-input cat-code-input">
                            <input type="text" name="label" value="{{ $emoji->label }}" placeholder="Label" class="console-input cat-label-input">
                            <input type="file" name="image" accept="image/*" class="cat-file">
                            <input type="number" name="sort_order" min="0" value="{{ $emoji->sort_order }}" class="console-input cat-sort-input">
                            <label class="cat-check">
                                <input type="checkbox" name="is_active" value="1" @checked($emoji->is_active)> Aktif
                            </label>
                            @if($emoji->image_url)
                                <label class="cat-check">
                                    <input type="checkbox" name="remove_image" value="1"> Hapus gambar
                                </label>
                            @endif
                            <button type="submit" class="console-btn sm neutral">Simpan</button>
                        </form>
                        <form action="{{ route('admin.chat-catalog.emojis.destroy', $emoji) }}" method="POST" onsubmit="return confirm('Hapus emoji ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="console-btn sm danger">Hapus</button>
                        </form>
                    </div>
                @empty
                    <p class="cat-empty">Belum ada emoji.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ══════════ STIKER ══════════ --}}
    <section x-show="tab === 'sticker'" x-cloak class="space-y-5">
        <div class="console-card p-4">
            <p class="admin-kicker mb-3">Tambah paket stiker</p>
            <form action="{{ route('admin.chat-catalog.packs.store') }}" method="POST" class="cat-row">
                @csrf
                <input type="text" name="label" value="{{ old('label') }}" placeholder="Nama paket (mis. Mood)" required class="console-input cat-label-input">
                <input type="text" name="key" value="{{ old('key') }}" placeholder="Key (opsional)" class="console-input cat-key-input">
                <input type="number" name="sort_order" min="0" placeholder="Urutan" class="console-input cat-sort-input">
                <button type="submit" class="console-btn sm">＋ Tambah paket</button>
            </form>
            @error('key')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
        </div>

        @forelse($packs as $pack)
            <div class="console-card p-4">
                <form action="{{ route('admin.chat-catalog.packs.update', $pack) }}" method="POST" class="cat-row">
                    @csrf
                    @method('PUT')
                    <span class="cat-pack-title mr-2">{{ $pack->label }}</span>
                    <input type="text" name="label" value="{{ $pack->label }}" required class="console-input cat-label-input">
                    <input type="text" name="key" value="{{ $pack->key }}" class="console-input cat-key-input">
                    <input type="number" name="sort_order" min="0" value="{{ $pack->sort_order }}" class="console-input cat-sort-input">
                    <label class="cat-check">
                        <input type="checkbox" name="is_active" value="1" @checked($pack->is_active)> Aktif
                    </label>
                    <button type="submit" class="console-btn sm neutral">Simpan</button>
                </form>

                <div class="mt-3 space-y-2">
                    <p class="admin-kicker">Stiker dalam paket ({{ $pack->items->count() }})</p>
                    @forelse($pack->items as $item)
                        <div class="cat-row">
                            <span class="cat-thumb">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->label }}">
                                @else
                                    {{ $item->emoji }}
                                @endif
                            </span>
                            <form action="{{ route('admin.chat-catalog.items.update', $item) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2 flex-grow">
                                @csrf
                                @method('PUT')
                                <input type="text" name="emoji" value="{{ $item->emoji }}" placeholder="🔥" class="console-input cat-emoji-input">
                                <input type="text" name="label" value="{{ $item->label }}" placeholder="Label" required class="console-input cat-label-input">
                                <input type="text" name="key" value="{{ $item->key }}" class="console-input cat-key-input">
                                <input type="file" name="image" accept="image/*" class="cat-file">
                                <input type="number" name="sort_order" min="0" value="{{ $item->sort_order }}" class="console-input cat-sort-input">
                                <label class="cat-check">
                                    <input type="checkbox" name="is_active" value="1" @checked($item->is_active)> Aktif
                                </label>
                                @if($item->image_url)
                                    <label class="cat-check">
                                        <input type="checkbox" name="remove_image" value="1"> Hapus gambar
                                    </label>
                                @endif
                                <button type="submit" class="console-btn sm neutral">Simpan</button>
                            </form>
                            <form action="{{ route('admin.chat-catalog.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus stiker ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="console-btn sm danger">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="cat-empty">Paket ini belum memiliki stiker.</p>
                    @endforelse
                </div>

                <form action="{{ route('admin.chat-catalog.items.store', $pack) }}" method="POST" enctype="multipart/form-data" class="cat-row mt-3">
                    @csrf
                    <input type="text" name="emoji" placeholder="🔥" class="console-input cat-emoji-input">
                    <input type="text" name="label" placeholder="Label stiker" required class="console-input cat-label-input">
                    <input type="text" name="key" placeholder="Key (opsional)" class="console-input cat-key-input">
                    <input type="file" name="image" accept="image/*" class="cat-file">
                    <input type="number" name="sort_order" min="0" placeholder="Urutan" class="console-input cat-sort-input">
                    <button type="submit" class="console-btn sm">＋ Tambah stiker</button>
                </form>
            </div>
        @empty
            <div class="console-card"><p class="console-empty">Belum ada paket stiker.</p></div>
        @endforelse
    </section>
</div>
@endsection
