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
        'cover_public_id',
        'creation_step',
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

    public function characterRelationships(): HasMany
    {
        return $this->hasMany(CharacterRelationship::class);
    }

    public function plotNotes(): HasMany
    {
        return $this->hasMany(PlotNote::class);
    }

    public function viewLogs(): HasMany
    {
        return $this->hasMany(NovelViewLog::class);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image_url;
    }

    /**
     * TiDB tidak menegakkan FOREIGN KEY (sintaksnya hanya di-parse), jadi
     * cascade "onDelete" diulang di level aplikasi agar tidak ada baris yatim.
     */
    protected static function booted(): void
    {
        static::deleting(function (Novel $novel) {
            $novel->genres()->detach();
            $novel->tags()->detach();

            foreach ($novel->chapters as $chapter) {
                $chapter->delete();
            }

            foreach ($novel->characters as $character) {
                $character->delete();
            }

            // Sudah habis saat karakter dihapus di atas, tetapi tetap dipanggil
            // untuk relasi yang salah satu sisinya bukan karakter milik novel ini.
            $novel->characterRelationships()->delete();

            $novel->reviews()->delete();
            $novel->bookmarks()->delete();
            $novel->readingHistories()->delete();
            $novel->viewLogs()->delete();

            ReadingSession::where('novel_id', $novel->id)->delete();
            UserListItem::where('novel_id', $novel->id)->delete();

            // Kolom ini memakai nullOnDelete: dikosongkan, bukan dihapus.
            $novel->plotNotes()->update(['novel_id' => null]);
        });
    }
}
