<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WriterEarning extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'description',
        'source',
        'period_start',
        'period_end',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'period_start' => 'date',
            'period_end' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'chapter_unlock' => 'Paid chapter unlocks',
            'membership' => 'Reader memberships',
            'tip' => 'Reader tips',
            'payout' => 'Payout',
            default => 'Earning',
        };
    }
}
