<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Novel extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id', 
        'title', 
        'alternative_title', 
        'slug', 
        'description', 
        'status', 
        'type', 
        'region',
        'language',
        'content_rating', 
        'view_count', 
        'rating_avg', 
        'cover_image',
        'is_featured',
        'cover_image_url',
        'cover_public_id'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function characters(): HasMany
    {
        return $this->hasMany(NovelCharacter::class)->orderBy('sort_order')->orderBy('id');
    }
}
