<?php

namespace App\Services;

use App\Models\Novel;
use App\Models\NovelViewLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NovelViewService
{
    public function recordView(Novel $novel): void
    {
        $novel->increment('view_count');

        $today = now()->toDateString();

        $log = NovelViewLog::firstOrCreate(
            ['novel_id' => $novel->id, 'viewed_on' => $today],
            ['views' => 0],
        );

        $log->increment('views');
    }

    /**
     * @return Collection<int, Novel>
     */
    public function trending(int $days = 7, int $limit = 10): Collection
    {
        $since = now()->subDays($days)->toDateString();

        return $this->trendingQuery($since)
            ->limit($limit)
            ->get();
    }

    /**
     * @return Builder<Novel>
     */
    public function trendingQuery(string $sinceDate): Builder
    {
        return Novel::query()
            ->with(['author', 'genres'])
            ->withCount(['chapters', 'bookmarks'])
            ->joinSub(
                NovelViewLog::query()
                    ->select('novel_id', DB::raw('SUM(views) as period_views'))
                    ->where('viewed_on', '>=', $sinceDate)
                    ->groupBy('novel_id'),
                'period_stats',
                'period_stats.novel_id',
                '=',
                'novels.id',
            )
            ->select('novels.*', 'period_stats.period_views')
            ->orderByDesc('period_stats.period_views');
    }

    public function periodLabel(int $days): string
    {
        return match ($days) {
            7 => '7 hari terakhir',
            30 => '30 hari terakhir',
            default => "{$days} hari terakhir",
        };
    }
}
