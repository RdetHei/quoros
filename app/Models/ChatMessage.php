<?php

namespace App\Models;

use App\Models\Concerns\HasCommunityReactions;
use App\Support\ChatCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasCommunityReactions;
    use HasFactory;

    protected $fillable = ['user_id', 'parent_id', 'body', 'sticker'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /** @return array{id: string, emoji: string, label: string, pack: string}|null */
    public function stickerMeta(): ?array
    {
        if (! $this->sticker) {
            return null;
        }

        return ChatCatalog::sticker($this->sticker);
    }

    protected static function booted(): void
    {
        static::deleting(function (ChatMessage $message) {
            $message->reactions()->delete();
        });
    }
}
