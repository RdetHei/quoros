<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoutSetting extends Model
{
    protected $fillable = [
        'user_id',
        'method',
        'account_name',
        'account_number',
        'currency',
        'auto_payout',
        'min_payout',
    ];

    protected function casts(): array
    {
        return [
            'auto_payout' => 'boolean',
            'min_payout' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMaskedAccountAttribute(): ?string
    {
        if (! $this->account_number) {
            return null;
        }

        return '•••• ' . substr($this->account_number, -4);
    }
}
