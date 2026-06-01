<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Novel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ReportableResolver
{
    /** @var array<string, class-string<Model>> */
    private const MAP = [
        'novel' => Novel::class,
        'comment' => Comment::class,
        'user' => User::class,
    ];

    public function resolve(string $type, int $id): Model
    {
        $class = self::MAP[$type] ?? null;

        if (! $class) {
            throw new InvalidArgumentException('Jenis laporan tidak valid.');
        }

        return $class::query()->findOrFail($id);
    }

    public function morphClass(string $type): string
    {
        $class = self::MAP[$type] ?? null;

        if (! $class) {
            throw new InvalidArgumentException('Jenis laporan tidak valid.');
        }

        return $class;
    }
}
