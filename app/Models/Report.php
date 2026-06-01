<?php

namespace App\Models;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'details',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reason' => ReportReason::class,
            'status' => ReportStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function reportableLabel(): string
    {
        $target = $this->reportable;

        if ($target instanceof Novel) {
            return 'Novel: '.$target->title;
        }

        if ($target instanceof Comment) {
            $target->loadMissing('chapter.novel');

            $novelTitle = $target->chapter?->novel?->title;

            return 'Komentar pada '.($novelTitle ?? 'Komentar #'.$target->id);
        }

        if ($target instanceof User) {
            return 'Pengguna: '.$target->name;
        }

        return class_basename($this->reportable_type).' #'.$this->reportable_id;
    }

    public function subjectUser(): ?User
    {
        $target = $this->reportable;

        if ($target instanceof User) {
            return $target;
        }

        if ($target instanceof Novel) {
            return $target->author;
        }

        if ($target instanceof Comment) {
            return $target->user;
        }

        return null;
    }
}
