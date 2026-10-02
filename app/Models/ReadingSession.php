<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingSession extends Model
{
    protected $fillable = [
        'session_uuid', 'user_id', 'novel_id',
        'started_at', 'last_active_at', 'ended_at', 'active_seconds',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'last_active_at' => 'datetime',
            'ended_at' => 'datetime',
            'active_seconds' => 'integer',
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

}
