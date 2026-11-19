<?php

namespace App\Models\Concerns;

use App\Models\Reaction;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasCommunityReactions
{
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function likes(): MorphMany
    {
        return $this->reactions()->where('type', 'like');
    }

    public function dislikes(): MorphMany
    {
        return $this->reactions()->where('type', 'dislike');
    }

    public function emojiReactions(): MorphMany
    {
        return $this->reactions()->whereNotIn('type', ['like', 'dislike']);
    }

    public function voteScore(): int
    {
        return (int) ($this->likes_count ?? $this->likes()->count())
            - (int) ($this->dislikes_count ?? $this->dislikes()->count());
    }

    public function currentUserVote(?int $userId): ?string
    {
        if (! $userId) {
            return null;
        }

        $reactions = $this->relationLoaded('reactions')
            ? $this->reactions
            : $this->reactions()->where('user_id', $userId)->get();

        $vote = $reactions->first(
            fn (Reaction $reaction) => $reaction->user_id === $userId
                && in_array($reaction->type, ['like', 'dislike'], true)
        );

        return $vote?->type;
    }

    /** @return list<array{emoji: string, count: int, mine: bool}> */
    public function groupedEmojis(?int $userId = null): array
    {
        $reactions = $this->relationLoaded('reactions')
            ? $this->reactions
            : $this->reactions()->get();

        return $reactions
            ->filter(fn (Reaction $reaction) => str_starts_with($reaction->type, 'e:'))
            ->groupBy('type')
            ->map(function ($group, string $type) use ($userId) {
                return [
                    'emoji' => substr($type, 2),
                    'count' => $group->count(),
                    'mine' => $userId ? $group->contains('user_id', $userId) : false,
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->all();
    }
}
