<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatEmoji;
use App\Models\ChatStickerItem;
use App\Models\ChatStickerPack;
use App\Support\ChatCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ChatCatalogController extends Controller
{
    public function index()
    {
        $emojis = ChatEmoji::query()->ordered()->get();

        $packs = ChatStickerPack::query()
            ->ordered()
            ->with(['items' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
            ->get();

        return view('admin.chat-catalog.index', compact('emojis', 'packs'));
    }

    public function storeEmoji(Request $request)
    {
        $data = $request->validate([
            'emoji' => ['required', 'string', 'max:32'],
            'label' => ['nullable', 'string', 'max:64'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        ChatEmoji::create([
            'emoji' => $data['emoji'],
            'label' => $data['label'] ?? null,
            'sort_order' => $data['sort_order'] ?? (int) ChatEmoji::query()->max('sort_order') + 1,
            'is_active' => true,
        ]);

        ChatCatalog::flush();

        return back()->with('success', 'Emoji berhasil ditambahkan.');
    }

    public function updateEmoji(Request $request, ChatEmoji $emoji)
    {
        $data = $request->validate([
            'emoji' => ['required', 'string', 'max:32'],
            'label' => ['nullable', 'string', 'max:64'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $emoji->update([
            'emoji' => $data['emoji'],
            'label' => $data['label'] ?? null,
            'sort_order' => $data['sort_order'] ?? $emoji->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        ChatCatalog::flush();

        return back()->with('success', 'Emoji berhasil diperbarui.');
    }

    public function destroyEmoji(ChatEmoji $emoji)
    {
        $emoji->delete();

        ChatCatalog::flush();

        return back()->with('success', 'Emoji berhasil dihapus.');
    }

    public function storePack(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:64'],
            'key' => ['nullable', 'string', 'max:64', 'alpha_dash', 'unique:chat_sticker_packs,key'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        ChatStickerPack::create([
            'key' => $data['key'] ?? $this->uniquePackKey($data['label']),
            'label' => $data['label'],
            'sort_order' => $data['sort_order'] ?? (int) ChatStickerPack::query()->max('sort_order') + 1,
            'is_active' => true,
        ]);

        ChatCatalog::flush();

        return back()->with('success', 'Paket stiker berhasil ditambahkan.');
    }

    public function updatePack(Request $request, ChatStickerPack $pack)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:64'],
            'key' => ['nullable', 'string', 'max:64', 'alpha_dash', Rule::unique('chat_sticker_packs', 'key')->ignore($pack->id)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $pack->update([
            'key' => $data['key'] ?? $pack->key,
            'label' => $data['label'],
            'sort_order' => $data['sort_order'] ?? $pack->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        ChatCatalog::flush();

        return back()->with('success', 'Paket stiker berhasil diperbarui.');
    }

    public function destroyPack(ChatStickerPack $pack)
    {
        $pack->delete();

        ChatCatalog::flush();

        return back()->with('success', 'Paket stiker beserta isinya berhasil dihapus.');
    }

    public function storeItem(Request $request, ChatStickerPack $pack)
    {
        $data = $request->validate([
            'emoji' => ['required', 'string', 'max:32'],
            'label' => ['required', 'string', 'max:64'],
            'key' => ['nullable', 'string', 'max:64', 'alpha_dash', 'unique:chat_sticker_items,key'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        ChatStickerItem::create([
            'chat_sticker_pack_id' => $pack->id,
            'key' => $data['key'] ?? $this->uniqueItemKey($data['label']),
            'emoji' => $data['emoji'],
            'label' => $data['label'],
            'sort_order' => $data['sort_order'] ?? (int) $pack->items()->max('sort_order') + 1,
            'is_active' => true,
        ]);

        ChatCatalog::flush();

        return back()->with('success', 'Stiker berhasil ditambahkan.');
    }

    public function updateItem(Request $request, ChatStickerItem $item)
    {
        $data = $request->validate([
            'emoji' => ['required', 'string', 'max:32'],
            'label' => ['required', 'string', 'max:64'],
            'key' => ['nullable', 'string', 'max:64', 'alpha_dash', Rule::unique('chat_sticker_items', 'key')->ignore($item->id)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $item->update([
            'key' => $data['key'] ?? $item->key,
            'emoji' => $data['emoji'],
            'label' => $data['label'],
            'sort_order' => $data['sort_order'] ?? $item->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        ChatCatalog::flush();

        return back()->with('success', 'Stiker berhasil diperbarui.');
    }

    public function destroyItem(ChatStickerItem $item)
    {
        $item->delete();

        ChatCatalog::flush();

        return back()->with('success', 'Stiker berhasil dihapus.');
    }

    private function uniquePackKey(string $label): string
    {
        return $this->uniqueKey($label, fn (string $key) => ChatStickerPack::where('key', $key)->exists());
    }

    private function uniqueItemKey(string $label): string
    {
        return $this->uniqueKey($label, fn (string $key) => ChatStickerItem::where('key', $key)->exists());
    }

    private function uniqueKey(string $source, callable $exists): string
    {
        $base = Str::slug($source) ?: 'item';
        $key = $base;
        $suffix = 2;

        while ($exists($key)) {
            $key = $base.'-'.$suffix++;
        }

        return $key;
    }
}
