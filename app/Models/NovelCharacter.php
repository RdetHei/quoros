<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NovelCharacter extends Model
{
    protected $fillable = [
        'novel_id',
        'name',
        'role',
        'description',
        'image',
        'sort_order',
    ];

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}
