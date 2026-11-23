<?php

namespace Database\Seeders;

use App\Models\ChatEmoji;
use App\Models\ChatStickerItem;
use App\Models\ChatStickerPack;
use Illuminate\Database\Seeder;

class ChatCatalogSeeder extends Seeder
{
    public function run(): void
    {
        if (ChatEmoji::query()->doesntExist()) {
            foreach (config('chat.emojis', []) as $index => $emoji) {
                ChatEmoji::create([
                    'emoji' => $emoji,
                    'label' => null,
                    'sort_order' => $index,
                    'is_active' => true,
                ]);
            }

            if ($this->command) {
                $this->command->info('Seeded '.count(config('chat.emojis', [])).' emojis.');
            }
        }

        foreach (config('chat.stickers', []) as $packIndex => $pack) {
            $model = ChatStickerPack::firstOrCreate(
                ['key' => $pack['id']],
                ['label' => $pack['label'], 'sort_order' => $packIndex, 'is_active' => true],
            );

            foreach ($pack['items'] as $itemIndex => $item) {
                ChatStickerItem::firstOrCreate(
                    ['key' => $item['id']],
                    [
                        'chat_sticker_pack_id' => $model->id,
                        'emoji' => $item['emoji'],
                        'label' => $item['label'],
                        'sort_order' => $itemIndex,
                        'is_active' => true,
                    ],
                );
            }
        }

        if ($this->command) {
            $this->command->info('Sticker packs seeded successfully!');
        }
    }
}
