<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatStickerItem extends Model
{
    protected $table = 'chat_sticker_items';

    protected $fillable = ['chat_sticker_pack_id', 'key', 'emoji', 'label', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function pack(): BelongsTo
    {
        return $this->belongsTo(ChatStickerPack::class, 'chat_sticker_pack_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
