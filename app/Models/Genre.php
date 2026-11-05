<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function novels(): BelongsToMany
    {
        return $this->belongsToMany(Novel::class);
    }

    /**
     * TiDB tidak menegakkan FOREIGN KEY, jadi baris pivot dibersihkan manual.
     */
    protected static function booted(): void
    {
        static::deleting(function (Genre $genre) {
            $genre->novels()->detach();
        });
    }
}
