<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterRelationship extends Model
{
    protected $fillable = [
        'novel_id',
        'character_id',
        'related_character_id',
        'related_name',
        'note',
        'status',
    ];

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(NovelCharacter::class, 'character_id');
    }

    public function relatedCharacter(): BelongsTo
    {
        return $this->belongsTo(NovelCharacter::class, 'related_character_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->relatedCharacter->name ?? $this->related_name ?? 'Unknown';
    }
}
