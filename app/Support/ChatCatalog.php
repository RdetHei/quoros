<?php

namespace App\Support;

class ChatCatalog
{
    /** @return list<string> */
    public static function emojis(): array
    {
        return config('chat.emojis', []);
    }

    /** @return list<array{id: string, label: string, items: list<array{id: string, emoji: string, label: string}>}> */
    public static function packs(): array
    {
        return config('chat.stickers', []);
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
