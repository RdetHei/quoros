<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatEmoji;
use App\Models\ChatStickerItem;
use App\Models\ChatStickerPack;
use App\Services\CloudinaryService;
use App\Support\ChatCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ChatCatalogController extends Controller
{
    public function __construct(
        private CloudinaryService $cloudinary,
    ) {}

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
            'emoji' => ['nullable', 'string', 'max:32', 'required_without:image'],
            'shortcode' => ['nullable', 'string', 'max:64', 'alpha_dash', 'unique:chat_emojis,shortcode'],
            'label' => ['nullable', 'string', 'max:64'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $image = $this->storeImage($request, 'emoji');

        ChatEmoji::create([
            'emoji' => $data['emoji'] ?? null,
            'shortcode' => $this->resolveShortcode($data['shortcode'] ?? null, $data['label'] ?? null),
            'label' => $data['label'] ?? null,
            'image_url' => $image['url'] ?? null,
            'image_public_id' => $image['public_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? (int) ChatEmoji::query()->max('sort_order') + 1,
            'is_active' => true,
        ]);

        ChatCatalog::flush();

        return back()->with('success', 'Emoji berhasil ditambahkan.');
    }

    public function updateEmoji(Request $request, ChatEmoji $emoji)
    {
        $data = $request->validate([
            'emoji' => ['nullable', 'string', 'max:32'],
            'shortcode' => ['nullable', 'string', 'max:64', 'alpha_dash', Rule::unique('chat_emojis', 'shortcode')->ignore($emoji->id)],
            'label' => ['nullable', 'string', 'max:64'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if (trim((string) ($data['emoji'] ?? '')) === '' && ! $request->hasFile('image') && ! $emoji->image_url) {
            return back()->withErrors(['emoji' => 'Isi emoji unicode atau unggah gambar.'])->withInput();
        }

        $attributes = [
            'emoji' => $data['emoji'] ?? null,
            'shortcode' => $this->resolveShortcode($data['shortcode'] ?? null, $data['label'] ?? null, $emoji->id),
            'label' => $data['label'] ?? null,
            'sort_order' => $data['sort_order'] ?? $emoji->sort_order,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            $this->cloudinary->deleteImage($emoji->image_public_id ?? '');
            $image = $this->storeImage($request, 'emoji');
            $attributes['image_url'] = $image['url'] ?? null;
            $attributes['image_public_id'] = $image['public_id'] ?? null;
        } elseif ($request->boolean('remove_image') && $emoji->image_url) {
            $this->cloudinary->deleteImage($emoji->image_public_id ?? '');
            $attributes['image_url'] = null;
            $attributes['image_public_id'] = null;
        }

        $emoji->update($attributes);

        ChatCatalog::flush();

        return back()->with('success', 'Emoji berhasil diperbarui.');
    }

    public function destroyEmoji(ChatEmoji $emoji)
    {
        $this->cloudinary->deleteImage($emoji->image_public_id ?? '');
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
        foreach ($pack->items as $item) {
            $this->cloudinary->deleteImage($item->image_public_id ?? '');
        }

        $pack->delete();

        ChatCatalog::flush();

        return back()->with('success', 'Paket stiker beserta isinya berhasil dihapus.');
    }

    public function storeItem(Request $request, ChatStickerPack $pack)
    {
        $data = $request->validate([
            'emoji' => ['nullable', 'string', 'max:32', 'required_without:image'],
            'label' => ['required', 'string', 'max:64'],
            'key' => ['nullable', 'string', 'max:64', 'alpha_dash', 'unique:chat_sticker_items,key'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $image = $this->storeImage($request, 'sticker');

        ChatStickerItem::create([
            'chat_sticker_pack_id' => $pack->id,
            'key' => $data['key'] ?? $this->uniqueItemKey($data['label']),
            'emoji' => $data['emoji'] ?? null,
            'label' => $data['label'],
            'image_url' => $image['url'] ?? null,
            'image_public_id' => $image['public_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? (int) $pack->items()->max('sort_order') + 1,
            'is_active' => true,
        ]);

        ChatCatalog::flush();

        return back()->with('success', 'Stiker berhasil ditambahkan.');
    }

    public function updateItem(Request $request, ChatStickerItem $item)
    {
        $data = $request->validate([
            'emoji' => ['nullable', 'string', 'max:32'],
            'label' => ['required', 'string', 'max:64'],
            'key' => ['nullable', 'string', 'max:64', 'alpha_dash', Rule::unique('chat_sticker_items', 'key')->ignore($item->id)],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $attributes = [
            'key' => $data['key'] ?? $item->key,
            'emoji' => $data['emoji'] ?? null,
            'label' => $data['label'],
            'sort_order' => $data['sort_order'] ?? $item->sort_order,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            $this->cloudinary->deleteImage($item->image_public_id ?? '');
            $image = $this->storeImage($request, 'sticker');
            $attributes['image_url'] = $image['url'] ?? null;
            $attributes['image_public_id'] = $image['public_id'] ?? null;
        } elseif ($request->boolean('remove_image') && $item->image_url) {
            $this->cloudinary->deleteImage($item->image_public_id ?? '');
            $attributes['image_url'] = null;
            $attributes['image_public_id'] = null;
        }

        $item->update($attributes);

        ChatCatalog::flush();

        return back()->with('success', 'Stiker berhasil diperbarui.');
    }

    public function destroyItem(ChatStickerItem $item)
    {
        $this->cloudinary->deleteImage($item->image_public_id ?? '');
        $item->delete();

        ChatCatalog::flush();

        return back()->with('success', 'Stiker berhasil dihapus.');
    }

    /** @return array{url: ?string, public_id: ?string}|null */
    private function storeImage(Request $request, string $kind): ?array
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $kind === 'sticker'
            ? $this->cloudinary->uploadSticker($request->file('image'))
            : $this->cloudinary->uploadEmoji($request->file('image'));
    }

    private function resolveShortcode(?string $shortcode, ?string $label, ?int $ignoreId = null): ?string
    {
        if (filled($shortcode)) {
            return $shortcode;
        }

        if (blank($label)) {
            return null;
        }

        return $this->uniqueKey($label, function (string $key) use ($ignoreId) {
            $query = ChatEmoji::where('shortcode', $key);

            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }

            return $query->exists();
        });
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
