<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlotNote extends Model
{
    protected $fillable = [
        'user_id',
        'novel_id',
        'title',
        'category',
        'content',
        'tags',
        'is_pinned',
        'is_open_question',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_pinned' => 'boolean',
            'is_open_question' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'scene_outline' => 'Scene outline',
            'character_arc' => 'Character arc',
            'world' => 'World & motifs',
            'continuity' => 'Continuity',
            default => 'Story note',
        };
    }
}
