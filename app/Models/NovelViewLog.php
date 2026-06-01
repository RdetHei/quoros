<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NovelViewLog extends Model
{
    protected $fillable = [
        'novel_id',
        'viewed_on',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'viewed_on' => 'date',
            'views' => 'integer',
        ];
    }

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}
