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
 */
class ChatCatalog
{
    /** @var list<string>|null */
    private static ?array $emojiCache = null;

    /** @var list<array{id: string, label: string, items: list<array{id: string, emoji: string, label: string}>}>|null */
    private static ?array $packCache = null;

    public static function flush(): void
    {
        self::$emojiCache = null;
        self::$packCache = null;
    }

    /** @return list<string> */
    public static function emojis(): array
    {
        if (self::$emojiCache !== null) {
            return self::$emojiCache;
        }

        $fallback = config('chat.emojis', []);

        try {
            if (! Schema::hasTable('chat_emojis')) {
                return self::$emojiCache = $fallback;
            }

            $rows = ChatEmoji::query()->active()->ordered()->pluck('emoji')->all();

            return self::$emojiCache = ($rows !== [] ? $rows : $fallback);
        } catch (Throwable) {
            return self::$emojiCache = $fallback;
        }
    }

    /** @return list<array{id: string, label: string, items: list<array{id: string, emoji: string, label: string}>}> */
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
                    'emoji' => $item->emoji,
                    'label' => $item->label,
                ])->all(),
            ])->all();
        } catch (Throwable) {
            return self::$packCache = $fallback;
        }
    }

    /** @return array<string, array{id: string, emoji: string, label: string, pack: string}> */
    public static function stickersById(): array
    {
        $map = [];

        foreach (self::packs() as $pack) {
            foreach ($pack['items'] as $item) {
                $map[$item['id']] = [
                    'id' => $item['id'],
                    'emoji' => $item['emoji'],
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

    /** @return array{id: string, emoji: string, label: string, pack: string}|null */
    public static function sticker(string $id): ?array
    {
        return self::stickersById()[$id] ?? null;
    }

    public static function isAllowedEmoji(string $emoji): bool
    {
        return in_array($emoji, self::emojis(), true);
    }
}
