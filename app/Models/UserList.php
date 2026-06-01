<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserList extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(UserListItem::class);
    }

    public function novels(): BelongsToMany
    {
        return $this->belongsToMany(Novel::class, 'user_list_items')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
