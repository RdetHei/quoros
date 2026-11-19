<?php

namespace App\Models;

use App\Models\Concerns\HasCommunityReactions;
use App\Support\ChatCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasCommunityReactions;
    use HasFactory;

    protected $fillable = ['user_id', 'novel_id', 'rating', 'content', 'sticker'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }

    /** @return array{id: string, emoji: string, label: string, pack: string}|null */
    public function stickerMeta(): ?array
    {
        return $this->sticker ? ChatCatalog::sticker($this->sticker) : null;
    }

    protected static function booted(): void
    {
        static::deleting(fn (Review $review) => $review->reactions()->delete());
    }
}
