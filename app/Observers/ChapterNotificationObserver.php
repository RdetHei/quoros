<?php

namespace App\Observers;

use App\Models\Chapter;
use App\Services\InAppNotificationService;
use Carbon\Carbon;

class ChapterNotificationObserver
{
    public function __construct(
        private InAppNotificationService $notifications,
    ) {}

    public function created(Chapter $chapter): void
    {
        if ($chapter->isPubliclyPublished()) {
            $this->notifications->notifyNewChapter($chapter);
        }
    }

    public function updated(Chapter $chapter): void
    {
        if (! $chapter->isPubliclyPublished()) {
            return;
        }

        if ($chapter->wasRecentlyCreated) {
            return;
        }

        if (! $chapter->wasChanged('status') && ! $chapter->wasChanged('published_at')) {
            return;
        }

        if ($this->wasPreviouslyPublished($chapter)) {
            return;
        }

        $this->notifications->notifyNewChapter($chapter);
    }

    private function wasPreviouslyPublished(Chapter $chapter): bool
    {
        $status = $chapter->getOriginal('status');
        $publishedAt = $chapter->getOriginal('published_at');

        if ($status !== 'published') {
            return false;
        }

        if ($publishedAt === null) {
            return true;
        }

        return Carbon::parse($publishedAt)->lte(now());
    }
}
