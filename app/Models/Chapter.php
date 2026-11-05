<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['novel_id', 'title', 'slug', 'content', 'file_path', 'status', 'published_at', 'order'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Scope a query to only include published chapters.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function isPubliclyPublished(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->published_at && $this->published_at->isFuture()) {
            return false;
        }

        return true;
    }

    /**
     * Terbitkan chapter berstatus "scheduled" yang waktunya sudah lewat.
     * Dipakai sebagai pengganti scheduled task karena InfinityFree
     * tidak menyediakan cron job.
     */
    public static function publishScheduledDue(?int $novelId = null): int
    {
        $query = static::query()
            ->where('status', 'scheduled')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if ($novelId !== null) {
            $query->where('novel_id', $novelId);
        }

        return $query->update(['status' => 'published']);
    }

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function getRouteIdentifierAttribute()
    {
        return $this->order > 0 ? $this->order : $this->id;
    }

    public function getWordCountAttribute(): int
    {
        if (empty($this->content)) {
            return 0;
        }

        $text = trim(strip_tags($this->content));
        if (empty($text)) {
            return 0;
        }

        if (preg_match('/[\x{4e00}-\x{9fff}\x{3040}-\x{30ff}\x{ac00}-\x{d7af}]/u', $text)) {
            return (int) ceil(mb_strlen($text) / 2);
        }

        return (int) str_word_count($text);
    }

    /**
     * TiDB tidak menegakkan FOREIGN KEY, jadi cascade diulang di level aplikasi.
     */
    protected static function booted(): void
    {
        static::deleting(function (Chapter $chapter) {
            foreach ($chapter->comments as $comment) {
                $comment->delete();
            }

            $chapter->readingHistories()->delete();
        });
    }

    /**
     * Get the previous chapter in the same novel.
     */
    public function previous($onlyPublished = true)
    {
        $query = static::where('novel_id', $this->novel_id)
            ->where('id', '<', $this->id);
        
        if ($onlyPublished) {
            $query->published();
        }

        return $query->orderBy('id', 'desc')->first();
    }

    /**
     * Get the next chapter in the same novel.
     */
    public function next($onlyPublished = true)
    {
        $query = static::where('novel_id', $this->novel_id)
            ->where('id', '>', $this->id);

        if ($onlyPublished) {
            $query->published();
        }

        return $query->orderBy('id', 'asc')->first();
    }
}
