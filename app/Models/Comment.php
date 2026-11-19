<?php

namespace App\Models;

use App\Models\Concerns\HasCommunityReactions;
use App\Support\ChatCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasCommunityReactions;
    use HasFactory;

    protected $fillable = ['user_id', 'chapter_id', 'parent_id', 'content', 'sticker'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    /** @return array{id: string, emoji: string, label: string, pack: string}|null */
    public function stickerMeta(): ?array
    {
        if (! $this->sticker) {
            return null;
        }

        return ChatCatalog::sticker($this->sticker);
    }

    /**
     * TiDB tidak menegakkan FOREIGN KEY, jadi cascade diulang di level aplikasi.
     */
    protected static function booted(): void
    {
        static::deleting(function (Comment $comment) {
            foreach ($comment->replies as $reply) {
                $reply->delete();
            }

            $comment->reactions()->delete();
        });
    }
}
