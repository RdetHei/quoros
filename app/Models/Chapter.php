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
