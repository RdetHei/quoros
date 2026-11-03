<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NovelCharacter extends Model
{
    protected $fillable = [
        'novel_id',
        'name',
        'tagline',
        'role',
        'cast_type',
        'description',
        'tags',
        'anchor',
        'current_chapter',
        'wants',
        'fears',
        'image',
        'sort_order',
        'image_url',
        'image_public_id'
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr(trim((string) $this->name), 0, 1));
    }

    public function getIsMainCastAttribute(): bool
    {
        return $this->cast_type === 'main';
    }

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }

    public function relationships(): HasMany
    {
        return $this->hasMany(CharacterRelationship::class, 'character_id');
    }
}
