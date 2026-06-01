<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\AuthorFollow;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\InAppNotification;
use App\Models\NovelRequest;
use Illuminate\Support\Collection;

class InAppNotificationService
{
    public function notifyNewChapter(Chapter $chapter): void
    {
        if (! $chapter->isPubliclyPublished()) {
            return;
        }

        $chapter->loadMissing('novel');
        $novel = $chapter->novel;

        $url = route('chapters.show', [
            'novel' => $novel->slug,
            'chapterSlug' => $chapter->slug,
        ]);

        $bookmarkUserIds = Bookmark::query()
            ->where('novel_id', $novel->id)
            ->where('user_id', '!=', $novel->author_id)
            ->pluck('user_id');

        $followerUserIds = AuthorFollow::query()
            ->where('author_id', $novel->author_id)
            ->where('follower_id', '!=', $novel->author_id)
            ->pluck('follower_id');

        $bookmarkOnly = $bookmarkUserIds->diff($followerUserIds)->values();
        $followerOnly = $followerUserIds->diff($bookmarkUserIds)->values();

        if ($bookmarkOnly->isNotEmpty()) {
            $this->insertForUsers($bookmarkOnly, NotificationType::ChapterNew, [
                'title' => 'Bab baru tersedia',
                'body' => "{$novel->title} — {$chapter->title}",
                'url' => $url,
                'novel_id' => $novel->id,
                'chapter_id' => $chapter->id,
            ]);
        }

        if ($followerOnly->isNotEmpty()) {
            $novel->loadMissing('author');
            $this->insertForUsers($followerOnly, NotificationType::AuthorChapterNew, [
                'title' => 'Penulis yang Anda ikuti merilis bab baru',
                'body' => "{$novel->author->name}: {$novel->title} — {$chapter->title}",
                'url' => $url,
                'novel_id' => $novel->id,
                'chapter_id' => $chapter->id,
            ]);
        }

        $both = $bookmarkUserIds->intersect($followerUserIds)->values();
        if ($both->isNotEmpty()) {
            $this->insertForUsers($both, NotificationType::ChapterNew, [
                'title' => 'Bab baru tersedia',
                'body' => "{$novel->title} — {$chapter->title}",
                'url' => $url,
                'novel_id' => $novel->id,
                'chapter_id' => $chapter->id,
            ]);
        }
    }

    public function notifyCommentReply(Comment $reply): void
    {
        $reply->loadMissing(['parent.user', 'user', 'chapter.novel']);
        $parent = $reply->parent;

        if (! $parent || ! $parent->user_id || $parent->user_id === $reply->user_id) {
            return;
        }

        $novel = $reply->chapter->novel;
        $url = route('chapters.show', [
            'novel' => $novel->slug,
            'chapterSlug' => $reply->chapter->slug,
        ]);

        InAppNotification::create([
            'user_id' => $parent->user_id,
            'type' => NotificationType::CommentReply,
            'data' => [
                'title' => 'Balasan komentar baru',
                'body' => ($reply->user->name ?? 'Seseorang').' membalas komentar Anda',
                'url' => $url.'#comment-'.$reply->id,
                'comment_id' => $reply->id,
                'parent_id' => $parent->id,
            ],
        ]);
    }

    public function notifyRequestStatus(NovelRequest $novelRequest, NotificationType $type): void
    {
        if (! in_array($type, [NotificationType::RequestFulfilled, NotificationType::RequestRejected], true)) {
            return;
        }

        $isFulfilled = $type === NotificationType::RequestFulfilled;

        $payload = [
            'title' => $isFulfilled ? 'Permintaan novel disetujui' : 'Permintaan novel ditolak',
            'body' => $isFulfilled
                ? "Permintaan \"{$novelRequest->title}\" telah dipenuhi."
                : "Permintaan \"{$novelRequest->title}\" tidak dapat dipenuhi.",
            'url' => route('requests.index'),
            'request_id' => $novelRequest->id,
            'request_title' => $novelRequest->title,
        ];

        InAppNotification::create([
            'user_id' => $novelRequest->user_id,
            'type' => $type,
            'data' => $payload,
        ]);
    }

    /**
     * @param  Collection<int, int>|array<int, int>  $userIds
     */
    private function insertForUsers(Collection|array $userIds, NotificationType $type, array $payload): void
    {
        $now = now();
        $rows = collect($userIds)->map(fn (int $userId) => [
            'user_id' => $userId,
            'type' => $type->value,
            'data' => json_encode($payload),
            'read_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        InAppNotification::insert($rows);
    }
}
