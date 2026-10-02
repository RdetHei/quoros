<?php

namespace App\Services;

use App\Models\Novel;
use App\Models\ReadingSession;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RecommendationService
{
    private const CACHE_VERSION = 'v1';

    public function forUser(User $user, int $limit = 4): Collection
    {
        $key = $this->cacheKey($user->id);
        $novelIds = Cache::get($key);

        if (! is_array($novelIds) || empty($novelIds)) {
            Cache::forget($key);
            $novelIds = $this->buildRecommendations($user, $limit)
                ->pluck('id')
                ->values()
                ->all();

            if (! empty($novelIds)) {
                Cache::put($key, $novelIds, now()->addMinutes(20));
            }
        }

        if (empty($novelIds)) {
            return $this->forGuest($limit);
        }

        $position = array_flip($novelIds);

        $results = $this->availableNovelsQuery()
            ->whereIn('novels.id', $novelIds)
            ->get()
            ->sortBy(fn (Novel $novel) => $position[$novel->id] ?? PHP_INT_MAX)
            ->values();

        if ($results->count() < $limit) {
            $existingIds = $results->pluck('id')->all();
            $more = $this->fallback($limit - $results->count(), [], $existingIds);
            $results = $results->concat($more)->unique('id')->take($limit)->values();
        }

        return $results;
    }

    public function forGuest(int $limit = 4): Collection
    {
        $key = 'fyp:guest:' . self::CACHE_VERSION;
        $novelIds = Cache::get($key);

        if (! is_array($novelIds) || empty($novelIds)) {
            Cache::forget($key);
            $novelIds = $this->fallback($limit)
                ->pluck('id')
                ->values()
                ->all();

            if (! empty($novelIds)) {
                Cache::put($key, $novelIds, now()->addMinutes(20));
            }
        }

        if (empty($novelIds)) {
            $novelIds = $this->availableNovelsQuery()
                ->latest('id')
                ->limit($limit)
                ->pluck('id')
                ->all();
        }

        if (empty($novelIds)) {
            return collect();
        }

        $position = array_flip($novelIds);

        $results = $this->availableNovelsQuery()
            ->whereIn('novels.id', $novelIds)
            ->get()
            ->sortBy(fn (Novel $novel) => $position[$novel->id] ?? PHP_INT_MAX)
            ->values();

        if ($results->count() < $limit) {
            $existingIds = $results->pluck('id')->all();
            $more = $this->fallback($limit - $results->count(), [], $existingIds);
            $results = $results->concat($more)->unique('id')->take($limit)->values();
        }

        return $results;
    }

    public function forgetForUser(int $userId): void
    {
        Cache::forget($this->cacheKey($userId));
        Cache::forget($this->discoveryCacheKey($userId));
    }

    public function discoveryIds(User $user, int $limit = 100): array
    {
        return Cache::remember(
            $this->discoveryCacheKey($user->id),
            now()->addMinutes(20),
            function () use ($user, $limit) {
                $signals = $this->collectSignals($user);

                if ($signals['signal_novel_ids'] === []) {
                    return [];
                }

                $preferences = $this->buildPreferences($signals['items']);
                $candidates = $this->candidateQuery(
                    $preferences,
                    array_keys($signals['followed_author_scores']),
                    [],
                )->limit($limit)->get();

                return $this->rank($candidates, $preferences, $signals['followed_author_scores'])
                    ->pluck('id')
                    ->values()
                    ->all();
            },
        );
    }

    private function buildRecommendations(User $user, int $limit): Collection
    {
        $signals = $this->collectSignals($user);
        $historyIds = $signals['history_ids'];
        $preferences = $this->buildPreferences($signals['items']);
        $confidence = count($signals['signal_novel_ids']);

        if ($confidence === 0) {
            $fallback = $this->fallback($limit, $historyIds);
            if ($fallback->count() < $limit) {
                $more = $this->fallback($limit - $fallback->count(), [], $fallback->pluck('id')->all());
                $fallback = $fallback->concat($more)->unique('id')->take($limit)->values();
            }

            return $fallback;
        }

        $candidates = $this->candidateQuery(
            $preferences,
            array_keys($signals['followed_author_scores']),
            $historyIds,
        )->limit(100)->get();

        $personalized = $this->rank($candidates, $preferences, $signals['followed_author_scores']);

        if ($personalized->isEmpty()) {
            $fallback = $this->fallback($limit, $historyIds);
            if ($fallback->count() < $limit) {
                $more = $this->fallback($limit - $fallback->count(), [], $fallback->pluck('id')->all());
                $fallback = $fallback->concat($more)->unique('id')->take($limit)->values();
            }

            return $fallback;
        }

        $personalizedRatio = match (true) {
            $confidence <= 2 => 0.60,
            $confidence <= 4 => 0.80,
            default => 0.90,
        };
        $personalizedLimit = max(1, (int) ceil($limit * $personalizedRatio));

        $results = $this->diversify($personalized, $personalizedLimit);

        if ($results->count() < $limit) {
            $fallback = $this->fallback($limit - $results->count(), $historyIds, $results->pluck('id')->all());
            $results = $results->concat($fallback)->unique('id')->values();
        }

        if ($results->count() < $limit) {
            $fallback = $this->fallback($limit - $results->count(), [], $results->pluck('id')->all());
            $results = $results->concat($fallback)->unique('id')->values();
        }

        return $results->take($limit)->values();
    }

    private function collectSignals(User $user): array
    {
        $items = collect();
        $historyIds = $user->readingHistories()->pluck('novel_id')->all();

        $histories = $user->readingHistories()
            ->with(['novel.genres', 'novel.tags'])
            ->get();
        foreach ($histories as $history) {
            if ($history->novel) {
                $items->push([
                    'novel' => $history->novel,
                    'weight' => 1.0,
                    'at' => $history->updated_at,
                ]);
            }
        }

        $readingTimeByNovel = ReadingSession::query()
            ->where('user_id', $user->id)
            ->selectRaw('novel_id, SUM(active_seconds) as active_seconds, MAX(last_active_at) as last_active_at')
            ->groupBy('novel_id')
            ->get();

        $readingNovels = Novel::with(['genres', 'tags'])
            ->whereIn('id', $readingTimeByNovel->pluck('novel_id'))
            ->get()
            ->keyBy('id');

        foreach ($readingTimeByNovel as $readingTime) {
            // The stored reading time is unlimited; FYP influence is capped at 20 minutes.
            $minutes = (int) floor(min(1200, (int) $readingTime->active_seconds) / 60);
            $durationWeight = match (true) {
                $minutes >= 20 => 2.0,
                $minutes >= 15 => 1.5,
                $minutes >= 10 => 1.25,
                $minutes >= 5 => 1.0,
                $minutes >= 2 => 0.5,
                $minutes >= 1 => 0.25,
                default => 0.0,
            };

            if ($durationWeight > 0) {
                $novel = $readingNovels->get($readingTime->novel_id);
                if ($novel) {
                    $items->push([
                        'novel' => $novel,
                        'weight' => $durationWeight,
                        'at' => $readingTime->last_active_at ? Carbon::parse($readingTime->last_active_at) : null,
                    ]);
                }
            }
        }

        $bookmarks = $user->bookmarks()
            ->with(['novel.genres', 'novel.tags'])
            ->get();
        foreach ($bookmarks as $bookmark) {
            if ($bookmark->novel) {
                $items->push([
                    'novel' => $bookmark->novel,
                    'weight' => 2.0,
                    'at' => $bookmark->updated_at,
                ]);
            }
        }

        $reviews = $user->reviews()
            ->with(['novel.genres', 'novel.tags'])
            ->get();
        foreach ($reviews as $review) {
            if ($review->novel) {
                $rating = (int) $review->rating;
                $items->push([
                    'novel' => $review->novel,
                    'weight' => match (true) {
                        $rating >= 4 => 2.5,
                        $rating === 3 => 0.5,
                        default => -2.0,
                    },
                    'at' => $review->updated_at,
                ]);
            }
        }

        $lists = $user->userLists()
            ->with(['novels.genres', 'novels.tags'])
            ->get();
        foreach ($lists as $list) {
            foreach ($list->novels as $novel) {
                $items->push([
                    'novel' => $novel,
                    'weight' => 1.5,
                    'at' => $novel->pivot?->created_at ?? $list->updated_at,
                ]);
            }
        }

        $followedAuthors = $user->following()
            ->with(['author.novels.genres', 'author.novels.tags'])
            ->get(['author_id', 'updated_at']);

        foreach ($followedAuthors as $follow) {
            foreach ($follow->author?->novels ?? [] as $novel) {
                $items->push([
                    'novel' => $novel,
                    'weight' => 1.5,
                    'at' => $follow->updated_at,
                ]);
            }
        }

        return [
            'items' => $items,
            'history_ids' => $historyIds,
            'signal_novel_ids' => $items->pluck('novel.id')->filter()->unique()->values()->all(),
            'followed_author_scores' => $followedAuthors->mapWithKeys(
                fn ($follow) => [$follow->author_id => $this->recencyMultiplier($follow->updated_at)]
            )->all(),
        ];
    }

    private function buildPreferences(Collection $items): array
    {
        $genres = [];
        $tags = [];

        foreach ($items as $item) {
            $weight = $item['weight'] * $this->recencyMultiplier($item['at']);

            foreach ($item['novel']->genres as $genre) {
                $genres[$genre->id] = ($genres[$genre->id] ?? 0) + $weight;
            }

            foreach ($item['novel']->tags as $tag) {
                $tags[$tag->id] = ($tags[$tag->id] ?? 0) + $weight;
            }
        }

        arsort($genres);
        arsort($tags);

        return [
            'genres' => $genres,
            'tags' => $tags,
        ];
    }

    private function candidateQuery(array $preferences, array $followedAuthorIds, array $historyIds)
    {
        $genreIds = array_keys(array_filter($preferences['genres'], fn ($score) => $score > 0));
        $tagIds = array_keys(array_filter($preferences['tags'], fn ($score) => $score > 0));
        $available = $this->availableNovelsQuery()
            ->whereNotIn('novels.id', $historyIds ?: [0]);

        if (! $genreIds && ! $tagIds && ! $followedAuthorIds) {
            return $available->whereRaw('1 = 0');
        }

        return $available
            ->where(function ($query) use ($genreIds, $tagIds, $followedAuthorIds) {
                if ($genreIds) {
                    $query->whereHas('genres', fn ($genres) => $genres->whereIn('genres.id', $genreIds));
                }

                if ($tagIds) {
                    $query->orWhereHas('tags', fn ($tags) => $tags->whereIn('tags.id', $tagIds));
                }

                if ($followedAuthorIds) {
                    $query->orWhereIn('author_id', $followedAuthorIds);
                }
            });
    }

    private function rank(Collection $candidates, array $preferences, array $followedAuthorScores): Collection
    {
        $maxViews = max(1, (int) $candidates->max('view_count'));
        $maxRecentViews = max(1, (int) $candidates->max('recent_views'));

        return $candidates->map(function (Novel $novel) use ($preferences, $followedAuthorScores, $maxViews, $maxRecentViews) {
            $genreScore = $this->featureScore($novel->genres, $preferences['genres']);
            $tagScore = $this->featureScore($novel->tags, $preferences['tags']);
            $authorScore = $followedAuthorScores[$novel->author_id] ?? 0.0;
            $popularityScore = min(1, (
                (($novel->view_count ?? 0) / $maxViews) * 0.5
                + (($novel->recent_views ?? 0) / $maxRecentViews) * 0.3
                + (min(5, (float) ($novel->rating_avg ?? 0)) / 5) * 0.2
            ));
            $freshnessScore = $this->freshnessScore($novel);

            $novel->recommendation_score = round(
                (0.40 * $genreScore)
                + (0.35 * $tagScore)
                + (0.10 * $authorScore)
                + (0.10 * $popularityScore)
                + (0.05 * $freshnessScore),
                6,
            );

            return $novel;
        })->sortByDesc('recommendation_score')->values();
    }

    private function diversify(Collection $novels, int $limit): Collection
    {
        $selected = collect();
        $remaining = $novels->values();

        while ($selected->count() < $limit && $remaining->isNotEmpty()) {
            $pickedIndex = $remaining->search(function (Novel $novel) use ($selected) {
                $key = $novel->genres->pluck('id')->sort()->implode('-');
                $recentKeys = $selected->take(-2)->map(
                    fn (Novel $item) => $item->genres->pluck('id')->sort()->implode('-')
                );

                return $recentKeys->filter(fn (string $recentKey) => $recentKey === $key)->count() < 2;
            });

            $pickedIndex = $pickedIndex === false ? 0 : $pickedIndex;
            $selected->push($remaining->pull($pickedIndex));
            $remaining = $remaining->values();
        }

        return $selected;
    }

    public function fallback(int $limit, array $excludedIds = [], array $additionalExcludedIds = []): Collection
    {
        $excluded = array_unique(array_merge($excludedIds, $additionalExcludedIds));
        $query = fn () => $this->availableNovelsQuery()->whereNotIn('novels.id', $excluded ?: [0]);

        $recent = $query()
            ->orderByRaw('COALESCE(latest_published_at, latest_chapter_created_at, novels.updated_at) DESC')
            ->limit(max(1, (int) ceil($limit * 0.4)))
            ->get();
        $trending = $query()
            ->orderByDesc('recent_views')
            ->limit(max(1, (int) ceil($limit * 0.3)))
            ->get();
        $featured = $query()
            ->where('is_featured', true)
            ->orderByDesc('view_count')
            ->limit(max(1, (int) ceil($limit * 0.2)))
            ->get();
        $rated = $query()
            ->orderByDesc('rating_avg')
            ->limit(max(1, (int) ceil($limit * 0.1)))
            ->get();

        $results = $recent->concat($trending)->concat($featured)->concat($rated)
            ->unique('id')
            ->take($limit)
            ->values();

        if ($results->count() < $limit) {
            $more = $query()
                ->whereNotIn('novels.id', array_unique(array_merge($excluded, $results->pluck('id')->all())) ?: [0])
                ->latest('id')
                ->limit($limit - $results->count())
                ->get();
            $results = $results->concat($more)->unique('id')->take($limit)->values();
        }

        return $results;
    }

    private function availableNovelsQuery()
    {
        $since = now()->subDays(7)->toDateString();

        return Novel::query()
            ->select('novels.*')
            ->with(['author', 'genres', 'tags', 'chapters' => function ($query) {
                $query->published()->latest('published_at')->latest('id')->take(3);
            }])
            ->whereHas('chapters', fn ($query) => $query->published())
            ->withMax(['chapters as latest_published_at' => fn ($query) => $query->published()], 'published_at')
            ->withMax(['chapters as latest_chapter_created_at' => fn ($query) => $query->published()], 'created_at')
            ->withSum(['viewLogs as recent_views' => fn ($query) => $query->where('viewed_on', '>=', $since)], 'views');
    }

    private function featureScore(Collection $features, array $preferences): float
    {
        $positiveTotal = collect($preferences)->filter(fn ($score) => $score > 0)->sortDesc()->take(3)->sum();
        if ($positiveTotal <= 0) {
            return 0.0;
        }

        $matched = $features->sum(fn ($feature) => $preferences[$feature->id] ?? 0);

        return max(0.0, min(1.0, $matched / $positiveTotal));
    }

    private function freshnessScore(Novel $novel): float
    {
        $chapter = $novel->chapters->first();
        if (! $chapter) {
            return 0.1;
        }

        $date = Carbon::parse($chapter->published_at ?? $chapter->created_at);
        $days = $date->diffInDays(now());

        return match (true) {
            $days <= 1 => 1.0,
            $days <= 7 => 0.7,
            $days <= 30 => 0.4,
            default => 0.1,
        };
    }

    private function recencyMultiplier(?Carbon $date): float
    {
        if (! $date) {
            return 0.20;
        }

        $days = $date->diffInDays(now());

        return match (true) {
            $days <= 7 => 1.00,
            $days <= 30 => 0.75,
            $days <= 90 => 0.45,
            default => 0.20,
        };
    }

    private function cacheKey(int $userId): string
    {
        return 'fyp:user:'.$userId.':'.self::CACHE_VERSION;
    }

    private function discoveryCacheKey(int $userId): string
    {
        return 'fyp:discovery:user:'.$userId.':'.self::CACHE_VERSION;
    }
}
