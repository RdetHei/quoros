<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ChatEmoji extends Model
{
    protected $table = 'chat_emojis';

    protected $fillable = ['emoji', 'shortcode', 'label', 'image_url', 'image_public_id', 'sort_order', 'is_active'];

    /** Nilai unik yang dipakai untuk reaksi & sisipan teks. */
    public function value(): string
    {
        if ($this->image_url) {
            return $this->shortcode ?: 'emoji-'.$this->id;
        }

        return $this->emoji ?: 'emoji-'.$this->id;
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
