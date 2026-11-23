<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatStickerPack extends Model
{
    protected $table = 'chat_sticker_packs';

    protected $fillable = ['key', 'label', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(ChatStickerItem::class, 'chat_sticker_pack_id')->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * TiDB tidak menegakkan FOREIGN KEY, jadi baris item dibersihkan manual.
     */
    protected static function booted(): void
    {
        static::deleting(function (ChatStickerPack $pack) {
            $pack->items()->delete();
        });
    }
}
