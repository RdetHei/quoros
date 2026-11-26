<?php

namespace App\Support;

use App\Models\ChatEmoji;
use App\Models\ChatStickerPack;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Katalog emoji & stiker untuk chat, komentar, dan review.
 *
 * Sumber utama adalah tabel DB (dikelola dari admin console), dengan
 * fallback ke config/chat.php agar aplikasi tetap berjalan saat tabel
 * belum di-migrate/seed.
 *
 * Emoji bisa berupa gambar hasil upload (image_url) maupun emoji unicode.
 * Setiap emoji punya "value" unik yang dipakai sebagai penanda reaksi
 * (disimpan di reactions.type dengan prefix "e:") dan sebagai shortcode
 * sisipan teks (:value:). Value dirender menjadi gambar saat tersedia.
 */
class ChatCatalog
{
    /** @var list<array{value: string, emoji: string, image: ?string, label: ?string}>|null */
    private static ?array $emojiCache = null;

    /** @var list<array{id: string, label: string, items: list<array{id: string, emoji: string, image: ?string, label: string}>}>|null */
    private static ?array $packCache = null;

    public static function flush(): void
    {
        self::$emojiCache = null;
        self::$packCache = null;
    }

    /**
     * Daftar emoji lengkap dengan data gambar/shortcode.
     *
     * @return list<array{value: string, emoji: string, image: ?string, label: ?string}>
     */
    public static function emojiEntries(): array
    {
        if (self::$emojiCache !== null) {
            return self::$emojiCache;
        }

        $fallback = array_map(
            fn (string $emoji) => ['value' => $emoji, 'emoji' => $emoji, 'image' => null, 'label' => null],
            config('chat.emojis', []),
        );

        try {
            if (! Schema::hasTable('chat_emojis') || ! Schema::hasColumn('chat_emojis', 'image_url')) {
                return self::$emojiCache = $fallback;
            }

            $rows = ChatEmoji::query()->active()->ordered()->get();

            if ($rows->isEmpty()) {
                return self::$emojiCache = $fallback;
            }

            return self::$emojiCache = $rows->map(fn (ChatEmoji $emoji) => [
                'value' => $emoji->value(),
                'emoji' => (string) ($emoji->emoji ?? ''),
                'image' => $emoji->image_url,
                'label' => $emoji->label,
            ])->all();
        } catch (Throwable) {
            return self::$emojiCache = $fallback;
        }
    }

    /** @return list<string> Nilai emoji valid untuk reaksi/sisipan. */
    public static function emojiValues(): array
    {
        return array_column(self::emojiEntries(), 'value');
    }

    /** Resolve value reaksi/shortcode kembali ke entri emoji. */
    public static function emojiByValue(string $value): ?array
    {
        foreach (self::emojiEntries() as $entry) {
            if ($entry['value'] === $value) {
                return $entry;
            }
        }

        return null;
    }

    public static function isAllowedEmoji(string $value): bool
    {
        return in_array($value, self::emojiValues(), true);
    }

    /** @return list<array{id: string, label: string, items: list<array{id: string, emoji: string, image: ?string, label: string}>}> */
    public static function packs(): array
    {
        if (self::$packCache !== null) {
            return self::$packCache;
        }

        $fallback = config('chat.stickers', []);

        try {
            if (! Schema::hasTable('chat_sticker_packs')) {
                return self::$packCache = $fallback;
            }

            $packs = ChatStickerPack::query()
                ->active()
                ->ordered()
                ->with(['items' => fn ($query) => $query->where('is_active', true)])
                ->get();

            if ($packs->isEmpty()) {
                return self::$packCache = $fallback;
            }

            return self::$packCache = $packs->map(fn (ChatStickerPack $pack) => [
                'id' => $pack->key,
                'label' => $pack->label,
                'items' => $pack->items->map(fn ($item) => [
                    'id' => $item->key,
                    'emoji' => (string) ($item->emoji ?? ''),
                    'image' => $item->image_url,
                    'label' => $item->label,
                ])->all(),
            ])->all();
        } catch (Throwable) {
            return self::$packCache = $fallback;
        }
    }

    /** @return array<string, array{id: string, emoji: string, image: ?string, label: string, pack: string}> */
    public static function stickersById(): array
    {
        $map = [];

        foreach (self::packs() as $pack) {
            foreach ($pack['items'] as $item) {
                $map[$item['id']] = [
                    'id' => $item['id'],
                    'emoji' => $item['emoji'],
                    'image' => $item['image'] ?? null,
                    'label' => $item['label'],
                    'pack' => $pack['id'],
                ];
            }
        }

        return $map;
    }

    /** @return list<string> */
    public static function stickerIds(): array
    {
        return array_keys(self::stickersById());
    }

    /** @return array{id: string, emoji: string, image: ?string, label: string, pack: string}|null */
    public static function sticker(string $id): ?array
    {
        return self::stickersById()[$id] ?? null;
    }

    /**
     * Ubah teks yang mengandung shortcode (:value:) menjadi HTML aman, dengan
     * gambar emoji untuk shortcode yang punya image_url. Seluruh teks di-escape
     * lebih dulu, hanya tag <img> yang disuntikkan.
     */
    public static function renderText(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        $html = e($text);

        foreach (self::emojiEntries() as $entry) {
            if (empty($entry['image']) || empty($entry['value'])) {
                continue;
            }

            $token = ':'.$entry['value'].':';

            if (! str_contains($html, $token)) {
                continue;
            }

            $img = '<img src="'.e($entry['image']).'" alt="'.e($entry['label'] ?? $entry['value'])
                .'" class="inline-block h-5 w-5 align-text-bottom" loading="lazy">';

            $html = str_replace($token, $img, $html);
        }

        return $html;
    }
}
