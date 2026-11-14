<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Models\AuthorFollow;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\CharacterRelationship;
use App\Models\Comment;
use App\Models\Novel;
use App\Models\NovelCharacter;
use App\Models\NovelRequest;
use App\Models\PayoutSetting;
use App\Models\PlotNote;
use App\Models\ReadingHistory;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use App\Models\WriterEarning;
use App\Models\Announcement;
use App\Services\CloudinaryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function index(Request $request)
    {
        if ($request->get('tab') === 'settings') {
            return redirect()->route('settings');
        }

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return match ($user->role) {
            'writer' => $this->writerDashboard($user),
            default => $this->readerDashboard($user),
        };
    }

    public function settings()
    {
        return redirect()->route('settings.v2');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['nullable', 'string', 'alpha_dash', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'bio' => 'nullable|string|max:500',
            'is_public_reading_list' => 'nullable|boolean',
            'auto_load_chapters' => 'nullable|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'bio']);
        $data['username'] = $request->filled('username') ? $request->username : null;
        $data['is_public_reading_list'] = $request->boolean('is_public_reading_list');
        $data['auto_load_chapters'] = $request->boolean('auto_load_chapters');

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_public_id) {
                $this->cloudinaryService->deleteImage($user->profile_photo_public_id);
            }
            $result = $this->cloudinaryService->uploadProfile($request->file('profile_photo'));
            $data['profile_photo_url'] = $result['url'];
            $data['profile_photo_public_id'] = $result['public_id'];
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function becomeWriter(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'user') {
            return back()->with('error', 'You already have contributor access.');
        }

        $user->role = 'writer';
        $user->save();

        return redirect()->route('writer.dashboard')->with('success', 'Congratulations! Your account has been successfully changed to Writer. You can now start creating your own novels.');
    }

    public function writerDashboardPage()
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);

        $currentNovel = $novels->first();

        $formatCount = function (int $n): string {
            if ($n >= 1000000) {
                return rtrim(rtrim(number_format($n / 1000000, 1), '0'), '.') . 'M';
            }
            if ($n >= 1000) {
                return rtrim(rtrim(number_format($n / 1000, 1), '0'), '.') . 'k';
            }

            return (string) $n;
        };

        $viewsDelta = $stats['viewsPrev7'] > 0
            ? round((($stats['viewsLast7'] - $stats['viewsPrev7']) / $stats['viewsPrev7']) * 100, 1)
            : ($stats['viewsLast7'] > 0 ? 100.0 : 0.0);

        $statsCards = [
            ['label' => 'Manuscript', 'value' => number_format($stats['totalWords']), 'sub' => 'words across ' . $stats['chapterCount'] . ' chapters', 'delta' => null],
            ['label' => 'Reader Activity', 'value' => $formatCount($stats['viewsLast7']), 'sub' => $stats['viewsToday'] . ' reads today', 'delta' => ($viewsDelta >= 0 ? '+ ' : '– ') . abs($viewsDelta) . '% vs previous 7 days', 'delta_dir' => $viewsDelta >= 0 ? 'up' : 'down'],
            ['label' => 'Reader Rating', 'value' => ($stats['totalReviews'] > 0 ? number_format($stats['avgRating'], 1) : '—') . ' / 5', 'sub' => $stats['totalReviews'] . ' ratings · ' . ($currentNovel->title ?? 'No novel yet'), 'delta' => null],
            ['label' => 'Unread Comments', 'value' => (string) $stats['unreadComments'], 'sub' => ($stats['unreadComments'] > 0 ? $stats['unreadComments'] . ' waiting for your reply' : 'No replies needed'), 'delta' => null, 'accent' => true],
        ];

        $recentChapters = $novels
            ->flatMap(fn ($n) => $n->chapters)
            ->sortByDesc('updated_at')
            ->take(4)
            ->values();

        $goalTarget = 1500;
        $wordsToday = (int) $novels
            ->flatMap(fn ($n) => $n->chapters)
            ->filter(fn ($ch) => $ch->updated_at && $ch->updated_at->isToday())
            ->sum(fn ($ch) => $ch->word_count);

        $dailyGoal = [
            'current' => $wordsToday,
            'target' => $goalTarget,
            'percent' => $goalTarget > 0 ? min(100, (int) round($wordsToday / $goalTarget * 100)) : 0,
            'remaining' => max(0, $goalTarget - $wordsToday),
        ];

        $nextTasks = [];
        $nextDraft = $drafts->first();
        if ($nextDraft) {
            $nextTasks[] = [
                'title' => 'Finish “' . $nextDraft->title . '”',
                'note' => ($nextDraft->novel->title ?? 'Your novel') . ' · ' . number_format($nextDraft->word_count) . ' words so far',
            ];
        }
        if ($stats['unreadComments'] > 0) {
            $nextTasks[] = [
                'title' => 'Reply to your readers',
                'note' => $stats['unreadComments'] . ' comment' . ($stats['unreadComments'] === 1 ? '' : 's') . ' waiting',
            ];
        }
        if ($stats['chapterCount'] === 0) {
            $nextTasks[] = ['title' => 'Write your first chapter', 'note' => 'Your story begins with a single page.'];
        }

        return view('writer.dashboard', array_merge($shared, compact(
            'statsCards', 'novels', 'drafts', 'comments', 'reviews', 'dailyGoal', 'nextTasks', 'stats', 'recentChapters'
        )));
    }

    public function writerAnalytics(Request $request)
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'analytics';
        $shared['breadcrumbs'] = ['Author Studio', 'Analytics'];
        $shared['dashboardTitle'] = 'Every chapter finds its readers.';
        $shared['dashboardSubtitle'] = null;

        $allNovels = $shared['allNovels'];
        $allNovelIds = $allNovels->pluck('id');
        $scopeNovelIds = $shared['selectedNovelId'] ? collect([$shared['selectedNovelId']]) : $allNovelIds;

        $range = (string) $request->get('range', '7');
        if (! in_array($range, ['7', '30', '90', 'all'], true)) {
            $range = '7';
        }

        $endDate = Carbon::today();
        if ($range === 'all') {
            $firstNovel = $allNovels->min('created_at');
            $startDate = $firstNovel ? Carbon::parse($firstNovel)->startOfDay() : Carbon::today()->subDays(364);
        } else {
            $startDate = Carbon::today()->subDays((int) $range - 1);
        }

        $spanDays = $startDate->diffInDays($endDate) + 1;
        if ($spanDays <= 14) {
            $bucketDays = 1;
        } elseif ($spanDays <= 92) {
            $bucketDays = 7;
        } else {
            $bucketDays = 30;
        }

        $logsByDate = DB::table('novel_view_logs')
            ->whereIn('novel_id', $scopeNovelIds)
            ->whereBetween('viewed_on', [$startDate->toDateString(), $endDate->toDateString()])
            ->select('viewed_on as date', DB::raw('SUM(views) as count'))
            ->groupBy('viewed_on')
            ->pluck('count', 'date');

        $chartLabels = [];
        $chartData = [];
        $cursor = $startDate->copy();
        while ($cursor->lte($endDate)) {
            $bucketEnd = $cursor->copy()->addDays($bucketDays - 1);
            if ($bucketEnd->gt($endDate)) {
                $bucketEnd = $endDate->copy();
            }
            $sum = 0;
            $d = $cursor->copy();
            while ($d->lte($bucketEnd)) {
                $sum += (int) $logsByDate->get($d->format('Y-m-d'), 0);
                $d->addDay();
            }
            $chartLabels[] = $bucketDays === 1
                ? $cursor->format('D')
                : ($bucketDays === 7 ? $cursor->format('d M') : $cursor->format('M Y'));
            $chartData[] = $sum;
            $cursor->addDays($bucketDays);
        }

        $viewsRange = array_sum($chartData);

        $prevEnd = $startDate->copy()->subDay();
        $prevStart = $prevEnd->copy()->subDays($spanDays - 1);
        $viewsPrev = (int) DB::table('novel_view_logs')->whereIn('novel_id', $scopeNovelIds)
            ->whereBetween('viewed_on', [$prevStart->toDateString(), $prevEnd->toDateString()])
            ->sum('views');

        $viewsDelta = ($range !== 'all' && $viewsPrev > 0)
            ? round((($viewsRange - $viewsPrev) / $viewsPrev) * 100, 1)
            : null;

        $rangeLabel = $startDate->format('M j') . ' – ' . $endDate->format('M j, Y');
        $bestDay = $chartData ? max($chartData) : 0;

        $kpis = [
            ['label' => 'Chapter Reads', 'value' => number_format($viewsRange), 'delta' => $viewsDelta !== null ? (($viewsDelta >= 0 ? '+ ' : '– ') . abs($viewsDelta) . '% vs previous period') : null, 'delta_dir' => ($viewsDelta ?? 0) >= 0 ? 'up' : 'down'],
            ['label' => 'Total Reads', 'value' => number_format($stats['totalViews']), 'sub' => 'All time across your novels', 'delta' => null],
            ['label' => 'Published Chapters', 'value' => (string) $stats['publishedChapterCount'], 'sub' => 'of ' . $stats['chapterCount'] . ' written', 'delta' => null],
            ['label' => 'Reader Rating', 'value' => ($stats['totalReviews'] > 0 ? number_format($stats['avgRating'], 1) : '—'), 'sub' => $stats['totalReviews'] . ' ratings · All time', 'delta' => null],
        ];

        $chapterIds = Chapter::whereIn('novel_id', $allNovelIds)->pluck('id');
        $readsByChapter = ReadingHistory::whereIn('chapter_id', $chapterIds)
            ->select('chapter_id', DB::raw('COUNT(*) as c'))->groupBy('chapter_id')->pluck('c', 'chapter_id');
        $commentsByChapter = Comment::whereIn('chapter_id', $chapterIds)
            ->select('chapter_id', DB::raw('COUNT(*) as c'))->groupBy('chapter_id')->pluck('c', 'chapter_id');

        $chapterTable = Chapter::whereIn('novel_id', $allNovelIds)
            ->published()
            ->orderBy('order')->orderBy('id')
            ->get(['id', 'novel_id', 'title', 'order', 'content'])
            ->map(fn ($ch) => [
                'id' => $ch->id,
                'num' => str_pad((string) ($ch->order ?: $ch->id), 2, '0', STR_PAD_LEFT),
                'title' => $ch->title,
                'reads' => (int) ($readsByChapter[$ch->id] ?? 0),
                'words' => (int) $ch->word_count,
                'comments' => (int) ($commentsByChapter[$ch->id] ?? 0),
            ])
            ->sortByDesc('reads')
            ->values();

        $topChapter = $chapterTable->first();
        $publishedTotal = $chapterTable->count();

        $viewsByNovelTotal = max(1, (int) $allNovels->sum('view_count'));
        $readsByNovel = $novels->map(fn ($n) => [
            'name' => $n->title,
            'readers' => (int) $n->view_count,
            'pct' => (int) round($n->view_count / $viewsByNovelTotal * 100),
        ])->sortByDesc('readers')->values();

        $shelfAdds = (int) $stats['totalBookmarks'];
        $followers = AuthorFollow::where('author_id', $user->id)->count();
        $sessionAvg = (int) DB::table('reading_sessions')->whereIn('novel_id', $allNovelIds)->avg('active_seconds');
        $avgSessionLabel = $sessionAvg > 0 ? floor($sessionAvg / 60) . 'm ' . ($sessionAvg % 60) . 's' : '—';

        return view('writer.analytics', array_merge($shared, compact(
            'kpis', 'chapterTable', 'readsByNovel', 'novels', 'stats', 'range', 'rangeLabel',
            'viewsRange', 'viewsDelta', 'bestDay', 'topChapter', 'publishedTotal',
            'shelfAdds', 'followers', 'avgSessionLabel', 'chartLabels', 'chartData'
        )));
    }

    public function writerEarnings(Request $request)
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'earnings';
        $shared['breadcrumbs'] = ['Author Studio', 'Earnings'];
        $shared['dashboardTitle'] = 'The value of your words.';
        $shared['dashboardSubtitle'] = null;

        $setting = $user->payoutSetting;
        $currency = $setting->currency ?? 'USD';
        $symbol = $currency === 'USD' ? '$' : $currency . ' ';
        $money = fn ($v) => $symbol . number_format((float) $v, 2);

        $period = (string) $request->get('period', 'month');
        if (! in_array($period, ['month', 'year', 'all'], true)) {
            $period = 'month';
        }

        $base = WriterEarning::where('user_id', $user->id);
        $periodQuery = (clone $base);
        if ($period === 'month') {
            $periodQuery->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month);
            $periodLabel = now()->format('F Y');
        } elseif ($period === 'year') {
            $periodQuery->whereYear('created_at', now()->year);
            $periodLabel = 'This year';
        } else {
            $periodLabel = 'All time';
        }

        $periodEarnings = (clone $periodQuery)->where('type', '!=', 'payout')->get();
        $netPeriod = (float) $periodEarnings->sum('amount');

        $prevStart = $period === 'month' ? now()->subMonthNoOverflow()->startOfMonth() : ($period === 'year' ? now()->subYear()->startOfYear() : null);
        $netPrev = 0;
        if ($prevStart) {
            $prevQuery = (clone $base)->where('type', '!=', 'payout');
            if ($period === 'month') {
                $prevQuery->whereYear('created_at', $prevStart->year)->whereMonth('created_at', $prevStart->month);
            } else {
                $prevQuery->whereYear('created_at', $prevStart->year);
            }
            $netPrev = (float) $prevQuery->sum('amount');
        }

        $delta = null;
        $deltaUp = true;
        if ($netPrev > 0) {
            $pct = round(($netPeriod - $netPrev) / $netPrev * 100, 1);
            $deltaUp = $pct >= 0;
            $delta = ($deltaUp ? '↑ ' : '↓ ') . abs($pct) . '% vs previous';
        }

        $available = (float) (clone $base)->where('type', '!=', 'payout')->where('status', 'available')->sum('amount');
        $pending = (float) (clone $base)->where('type', '!=', 'payout')->where('status', 'pending')->sum('amount');
        $lifetime = (float) (clone $base)->where('type', '!=', 'payout')->sum('amount');

        $periodCards = [
            ['label' => 'Net Earnings · ' . $periodLabel, 'value' => $money($netPeriod), 'delta' => $delta, 'delta_up' => $deltaUp],
            ['label' => 'Available for Payout', 'value' => $money($available), 'sub' => $available > 0 ? 'Ready to withdraw' : 'Nothing cleared yet'],
            ['label' => 'Pending Clearance', 'value' => $money($pending), 'sub' => $pending > 0 ? 'Clears on the next cycle' : 'No pending earnings'],
            ['label' => 'Lifetime Earnings', 'value' => $money($lifetime), 'sub' => 'Across your published novels'],
        ];

        $months = [];
        $maxMonth = 1;
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonthsNoOverflow($i);
            $sum = (float) (clone $base)
                ->where('type', '!=', 'payout')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
            $months[$date->format('M')] = $sum;
            $maxMonth = max($maxMonth, $sum);
        }

        $icons = ['chapter_unlock' => 'bookmark', 'membership' => 'heart', 'tip' => 'gift'];
        $revenueBreakdown = [];
        foreach (['chapter_unlock', 'membership', 'tip'] as $type) {
            $group = $periodEarnings->where('type', $type);
            $sum = (float) $group->sum('amount');
            $pct = $netPeriod > 0 ? round($sum / $netPeriod * 100, 1) : 0;
            $revenueBreakdown[] = [
                'icon' => $icons[$type],
                'title' => (new WriterEarning(['type' => $type]))->type_label,
                'value' => $money($sum),
                'sub' => $pct . '% of net earnings · ' . $group->count() . ' entries',
            ];
        }

        $transactions = (clone $base)->latest()->take(15)->get()->map(function (WriterEarning $e) use ($symbol) {
            $sign = $e->type === 'payout' ? '–' : '+';
            $periodText = $e->period_start
                ? $e->period_start->format('M j') . ($e->period_end ? ' – ' . $e->period_end->format('M j') : '')
                : $e->created_at->format('M j, Y');

            return [
                'name' => $e->description ?: $e->type_label,
                'period' => $periodText,
                'source' => $e->source ?: '—',
                'amount' => $sign . $symbol . number_format((float) $e->amount, 2),
                'status' => ucfirst($e->status),
                'is_payout' => $e->type === 'payout',
            ];
        });

        $payout = [
            'method' => $setting->method ?? 'bank',
            'method_label' => ($setting->method ?? 'bank') === 'paypal' ? 'PayPal' : 'Bank account',
            'account' => $setting?->masked_account,
            'account_name' => $setting?->account_name,
            'currency' => $currency,
            'auto_payout' => $setting->auto_payout ?? true,
            'min_payout' => (float) ($setting->min_payout ?? 50),
            'available' => $available,
            'configured' => (bool) $setting,
        ];

        return view('writer.earnings', array_merge($shared, compact(
            'novels', 'periodCards', 'revenueBreakdown', 'transactions', 'months', 'maxMonth', 'payout', 'period', 'periodLabel'
        )));
    }

    public function writerNovels(Request $request)
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'novels';
        $shared['breadcrumbs'] = ['Author Studio', 'My Novels'];
        $shared['dashboardTitle'] = 'A shelf of stories, all yours.';
        $shared['dashboardSubtitle'] = null;

        $shelf = $novels->map(function (Novel $novel) {
            $published = $novel->published_chapters_count;
            $total = $novel->chapters_count;

            if ($published === 0) {
                $state = 'draft';
            } elseif ($novel->status === 'complete') {
                $state = 'completed';
            } elseif ($novel->status === 'hiatus') {
                $state = 'hiatus';
            } else {
                $state = 'serializing';
            }

            return [
                'id' => $novel->id,
                'title' => $novel->title,
                'slug' => $novel->slug,
                'genre' => $novel->genres->pluck('name')->first() ?: 'Uncategorised',
                'desc' => $novel->description ?: 'No description yet — you can add one while editing this novel.',
                'words' => (int) $novel->chapters->sum(fn ($ch) => $ch->word_count),
                'chapters' => $total,
                'published' => $published,
                'pct' => $total > 0 ? (int) round($published / $total * 100) : 0,
                'state' => $state,
                'cover_url' => $novel->cover_url,
                'readers' => (int) $novel->bookmarks_count,
                'rating' => round((float) $novel->reviews_avg_rating, 1),
                'updated_at' => $novel->updated_at,
                'created_at' => $novel->created_at,
            ];
        });

        $statusCounts = [
            'all' => $shelf->count(),
            'serializing' => $shelf->where('state', 'serializing')->count(),
            'completed' => $shelf->where('state', 'completed')->count(),
            'hiatus' => $shelf->where('state', 'hiatus')->count(),
            'draft' => $shelf->where('state', 'draft')->count(),
        ];

        $status = $request->get('status', 'all');
        if (! in_array($status, ['all', 'serializing', 'completed', 'hiatus', 'draft'], true)) {
            $status = 'all';
        }

        $filtered = $status === 'all' ? $shelf : $shelf->where('state', $status)->values();

        $sort = $request->get('sort', 'updated');
        $filtered = match ($sort) {
            'created' => $filtered->sortByDesc('created_at')->values(),
            'title' => $filtered->sortBy(fn ($n) => strtolower($n['title']))->values(),
            'words' => $filtered->sortByDesc('words')->values(),
            'readers' => $filtered->sortByDesc('readers')->values(),
            default => $filtered->sortByDesc('updated_at')->values(),
        };

        $nextToPublish = $drafts->first();
        $nextDraftWords = $nextToPublish ? $nextToPublish->word_count : 0;

        $librarySummary = [
            'published_chapters' => $stats['publishedChapterCount'],
            'total_novels' => $novels->count(),
            'total_words' => number_format($stats['totalWords']),
        ];

        return view('writer.novels', array_merge($shared, compact(
            'filtered', 'shelf', 'novels', 'stats', 'statusCounts', 'status', 'sort',
            'librarySummary', 'nextToPublish', 'nextDraftWords'
        )));
    }

    public function writerChapters(Request $request)
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'chapters';
        $shared['breadcrumbs'] = ['Author Studio', 'Manuscript'];
        $shared['dashboardTitle'] = 'Welcome back, ' . explode(' ', $user->name)[0] . '.';
        $shared['dashboardSubtitle'] = 'The morning is quiet. Your next page is waiting.';

        $selectedNovel = $novels->firstWhere('id', (int) $request->get('novel_id')) ?? $novels->first();

        if (! $selectedNovel) {
            return view('writer.chapters', array_merge($shared, [
                'stats' => $stats,
                'novels' => $novels,
                'selectedNovel' => null,
                'chaptersList' => collect(),
                'activeChapter' => null,
                'hub' => null,
                'cast' => collect(),
            ]));
        }

        // Terbitkan chapter terjadwal yang waktunya sudah lewat agar status di
        // Author Studio sinkron dengan yang tampil di halaman publik.
        Chapter::publishScheduledDue($selectedNovel->id);

        $chapters = $selectedNovel->chapters()
            ->orderByRaw('COALESCE(NULLIF(`order`, 0), id) asc')
            ->orderBy('id')
            ->get();

        $activeChapterId = (int) $request->get('chapter_id');
        $activeChapter = $chapters->firstWhere('id', $activeChapterId) ?? $chapters->last();

        $chaptersList = $chapters->values()->map(function ($ch, $index) use ($activeChapter) {
            return [
                'id' => $ch->id,
                'num' => str_pad((string) ($ch->order ?: $index + 1), 2, '0', STR_PAD_LEFT),
                'title' => $ch->title,
                'words' => number_format($ch->word_count) . ' words',
                'status' => $ch->status_label,
                'status_class' => $ch->isPubliclyPublished() ? 'published' : 'draft',
                'active' => $activeChapter && $ch->id === $activeChapter->id,
            ];
        });

        $selectedNovel->loadMissing('characters');

        $hub = [
            'reads' => (int) $selectedNovel->view_count,
            'rating' => round((float) $selectedNovel->reviews_avg_rating, 1),
            'rating_count' => (int) $selectedNovel->reviews_count,
            'chapters' => $chapters->count(),
            'words' => (int) $chapters->sum(fn ($c) => $c->word_count),
            'status' => $selectedNovel->status,
        ];

        return view('writer.chapters', array_merge($shared, compact(
            'novels', 'drafts', 'chaptersList', 'stats', 'selectedNovel', 'activeChapter', 'hub'
        ), ['cast' => $selectedNovel->characters]));
    }

    public function writerReaderPreview(Request $request)
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'reader';
        $shared['breadcrumbs'] = ['Author Studio', 'Reader Mode'];
        $shared['showCreateBtn'] = false;

        $selectedNovel = $novels->firstWhere('id', (int) $request->get('novel_id')) ?? $novels->first();
        $shared['dashboardTitle'] = $selectedNovel->title ?? 'Reader Mode';
        $shared['dashboardSubtitle'] = null;

        $readerChapter = null;
        $chapterPosition = null;

        if ($selectedNovel) {
            $chapters = $selectedNovel->chapters->values();
            $activeId = (int) $request->get('chapter_id');
            $index = $chapters->search(fn ($c) => $c->id === $activeId);
            if ($index === false) {
                $index = $chapters->count() - 1;
            }
            $chapter = $chapters->get($index);

            if ($chapter) {
                $prev = $index > 0 ? $chapters->get($index - 1) : null;
                $next = $index < $chapters->count() - 1 ? $chapters->get($index + 1) : null;

                $plain = trim(strip_tags((string) $chapter->content));
                $paragraphs = $plain === ''
                    ? ['This chapter has no content yet. Start writing to see the preview here.']
                    : array_values(array_filter(preg_split('/\n\s*\n/', $plain)));

                $readerChapter = [
                    'part' => mb_strtoupper($selectedNovel->title),
                    'chapter_label' => 'CHAPTER ' . str_pad((string) ($chapter->order ?: $index + 1), 2, '0', STR_PAD_LEFT),
                    'title' => $chapter->title,
                    'word_count' => number_format($chapter->word_count) . ' WORDS · ' . mb_strtoupper($chapter->status),
                    'prev' => $prev ? ['label' => 'PREVIOUS · CHAPTER ' . str_pad((string) ($prev->order ?: $index), 2, '0', STR_PAD_LEFT), 'title' => $prev->title] : null,
                    'next' => $next ? ['label' => 'NEXT · CHAPTER ' . str_pad((string) ($next->order ?: $index + 2), 2, '0', STR_PAD_LEFT), 'title' => $next->title] : null,
                    'paragraphs' => $paragraphs,
                ];

                $chapterPosition = 'CHAPTER ' . str_pad((string) ($chapter->order ?: $index + 1), 2, '0', STR_PAD_LEFT) . ' OF ' . str_pad((string) $chapters->count(), 2, '0', STR_PAD_LEFT);
            }
        }

        return view('writer.reader', array_merge($shared, compact(
            'novels', 'selectedNovel', 'readerChapter', 'chapterPosition'
        )));
    }

    public function writerCodex(Request $request)
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'codex';
        $shared['breadcrumbs'] = ['Author Studio', 'Character Codex'];
        $shared['dashboardTitle'] = 'Know the people inside your story.';

        $palette = ['#c7a64a', '#8b6b3a', '#a3835a', '#6b8b7a', '#7a6b8b', '#5a6b8b'];

        $selectedNovel = $novels->firstWhere('id', (int) $request->get('novel_id')) ?? $novels->first();

        if (! $selectedNovel) {
            return view('writer.codex', array_merge($shared, [
                'novels' => $novels,
                'selectedNovel' => null,
                'cast' => collect(),
                'tabs' => [
                    ['key' => 'all', 'label' => 'All profiles', 'count' => 0],
                    ['key' => 'main', 'label' => 'Main cast', 'count' => 0],
                    ['key' => 'supporting', 'label' => 'Supporting', 'count' => 0],
                ],
                'filter' => 'all',
                'selectedCharacter' => null,
                'unresolved' => 0,
            ]));
        }

        $selectedNovel->loadMissing(['characters', 'characterRelationships.relatedCharacter']);

        $cast = $selectedNovel->characters->values()->map(function (NovelCharacter $c, int $i) use ($palette) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'tagline' => $c->tagline ?: ($c->role ?: 'No tagline yet'),
                'role' => $c->role ?: 'Unassigned',
                'cast_type' => $c->cast_type,
                'is_main' => $c->is_main_cast,
                'badge' => $c->is_main_cast ? ($c->role ?: 'Main Cast') : 'Supporting',
                'tags' => $c->tags ?? [],
                'anchor' => $c->anchor ?: '—',
                'current_chapter' => $c->current_chapter ?: '—',
                'wants' => $c->wants ?: ($c->description ?: 'Not written yet.'),
                'fears' => $c->fears ?: 'Not written yet.',
                'description' => $c->description,
                'image_url' => $c->image_url,
                'initials' => $c->initials,
                'color' => $palette[$i % count($palette)],
            ];
        });

        $mainCount = $cast->where('is_main', true)->count();
        $supportingCount = $cast->count() - $mainCount;

        $filter = (string) $request->get('filter', 'all');
        if (! in_array($filter, ['all', 'main', 'supporting'], true)) {
            $filter = 'all';
        }

        $visibleCast = match ($filter) {
            'main' => $cast->where('is_main', true)->values(),
            'supporting' => $cast->where('is_main', false)->values(),
            default => $cast,
        };

        $tabs = [
            ['key' => 'all', 'label' => 'All profiles', 'count' => $cast->count()],
            ['key' => 'main', 'label' => 'Main cast', 'count' => $mainCount],
            ['key' => 'supporting', 'label' => 'Supporting', 'count' => $supportingCount],
        ];

        $relationships = $selectedNovel->characterRelationships;
        $unresolved = $relationships->where('status', 'unresolved')->count();

        $selectedId = (int) $request->get('character_id');
        $selectedArray = $cast->firstWhere('id', $selectedId)
            ?? $cast->where('is_main', true)->first()
            ?? $cast->first();

        $selectedCharacter = null;
        if ($selectedArray) {
            $rels = $relationships->where('character_id', $selectedArray['id'])->values()->map(function (CharacterRelationship $r, int $i) use ($palette, $cast) {
                $related = $r->related_character_id ? $cast->firstWhere('id', $r->related_character_id) : null;

                return [
                    'id' => $r->id,
                    'name' => $related['name'] ?? $r->related_name ?? 'Unknown',
                    'note' => $r->note ?: 'No note yet.',
                    'status' => strtoupper($r->status),
                    'initials' => strtoupper(substr($related['name'] ?? $r->related_name ?? 'U', 0, 1)),
                    'color' => $palette[$i % count($palette)],
                ];
            });

            $linkedNote = $selectedNovel->plotNotes()->latest()->first();

            $selectedCharacter = array_merge($selectedArray, [
                'relationships' => $rels,
                'linked_note' => $linkedNote ? [
                    'title' => $linkedNote->title,
                    'link' => 'Open note',
                    'url' => route('writer.plot', ['novel_id' => $selectedNovel->id, 'note_id' => $linkedNote->id]),
                ] : null,
            ]);
        }

        $otherCharacters = $cast->where('id', '!=', $selectedArray['id'] ?? 0)->values();

        return view('writer.codex', array_merge($shared, compact(
            'novels', 'selectedNovel', 'cast', 'visibleCast', 'tabs', 'filter',
            'selectedCharacter', 'unresolved', 'supportingCount', 'otherCharacters'
        )));
    }

    public function writerComments(Request $request)
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'comments';
        $shared['breadcrumbs'] = ['Author Studio', 'Comments'];
        $shared['dashboardTitle'] = 'A conversation beyond the page.';

        $allNovelIds = $shared['allNovels']->pluck('id');
        $chapterIds = Chapter::whereIn('novel_id', $allNovelIds)->pluck('id');

        $palette = ['#c7a64a', '#6b8b7a', '#7a6b8b', '#8b6b3a', '#a3835a', '#5a6b8b'];
        $initialsOf = function (?string $name): string {
            $parts = preg_split('/\s+/', trim((string) $name));

            return strtoupper(substr($parts[0] ?? 'R', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
        };

        $sort = $request->get('sort') === 'oldest' ? 'asc' : 'desc';

        $allTop = Comment::with([
            'user:id,name',
            'chapter' => fn ($q) => $q->select('id', 'novel_id', 'title', 'order')->with('novel:id,slug'),
            'replies' => fn ($q) => $q->with(['user:id,name', 'likes' => fn ($r) => $r->where('type', 'like')])->oldest(),
            'likes' => fn ($q) => $q->where('type', 'like'),
        ])
            ->whereIn('chapter_id', $chapterIds)
            ->whereNull('parent_id')
            ->orderBy('created_at', $sort)
            ->get();

        $others = $allTop->where('user_id', '!=', $user->id);
        $unreadCount = $others->filter(fn ($c) => $c->replies->where('user_id', $user->id)->isEmpty())->count();
        $noReplyCount = $others->filter(fn ($c) => $c->replies->isEmpty())->count();

        $filter = (string) $request->get('filter', 'all');
        if (! in_array($filter, ['all', 'unread', 'needs_reply'], true)) {
            $filter = 'all';
        }

        $chapterFilter = (int) $request->get('chapter');

        $list = match ($filter) {
            'unread' => $others->filter(fn ($c) => $c->replies->where('user_id', $user->id)->isEmpty()),
            'needs_reply' => $others->filter(fn ($c) => $c->replies->isEmpty()),
            default => $allTop,
        };

        if ($chapterFilter) {
            $list = $list->where('chapter_id', $chapterFilter);
        }

        $list = $list->values();

        $conversations = $list->map(function (Comment $c, int $i) use ($palette, $user, $initialsOf) {
            $needsReply = $c->user_id !== $user->id && $c->replies->where('user_id', $user->id)->isEmpty();

            return [
                'id' => $c->id,
                'initials' => $initialsOf($c->user->name ?? null),
                'color' => $palette[$i % count($palette)],
                'name' => $c->user->name ?? 'Reader',
                'time' => $c->created_at->diffForHumans(null, true, true),
                'new' => $needsReply,
                'text' => $c->content,
                'chapter' => 'Ch ' . ($c->chapter->order ?: $c->chapter_id) . ' · ' . ($c->chapter->title ?? ''),
            ];
        });

        $selectedId = (int) $request->get('comment');
        $selected = $list->firstWhere('id', $selectedId) ?? $list->first();

        $activeThread = null;
        if ($selected) {
            $thread = [[
                'type' => 'reader',
                'initials' => $initialsOf($selected->user->name ?? null),
                'color' => '#c7a64a',
                'name' => $selected->user->name ?? 'Reader',
                'meta' => 'READER · ' . $selected->created_at->format('M j, Y g:i A'),
                'text' => $selected->content,
                'likes' => $selected->likes->count(),
            ]];

            foreach ($selected->replies as $reply) {
                $isAuthor = $reply->user_id === $user->id;
                $thread[] = [
                    'type' => $isAuthor ? 'author' : 'reader',
                    'indented' => ! $isAuthor,
                    'initials' => $initialsOf($reply->user->name ?? null),
                    'color' => $isAuthor ? '#c7a64a' : '#8b6b3a',
                    'name' => $isAuthor ? 'You' : ($reply->user->name ?? 'Reader'),
                    'meta' => ($isAuthor ? 'AUTHOR' : 'READER') . ' · ' . $reply->created_at->format('M j, Y g:i A'),
                    'text' => $reply->content,
                    'likes' => $reply->likes->count(),
                ];
            }

            $needsReply = $selected->user_id !== $user->id && $selected->replies->where('user_id', $user->id)->isEmpty();

            $activeThread = [
                'comment_id' => $selected->id,
                'chapter_id' => $selected->chapter_id,
                'chapter' => 'CHAPTER ' . str_pad((string) ($selected->chapter->order ?: $selected->chapter_id), 2, '0', STR_PAD_LEFT),
                'title' => $selected->chapter->title ?? 'Chapter',
                'novel_slug' => $selected->chapter->novel->slug ?? null,
                'chapter_order' => $selected->chapter->order ?: $selected->chapter_id,
                'status_badge' => $needsReply ? 'NEEDS REPLY' : 'REPLIED',
                'comments' => $thread,
            ];
        }

        $chaptersWithComments = Chapter::whereIn('id', $chapterIds)
            ->whereHas('comments')
            ->orderBy('order')->orderBy('id')
            ->get(['id', 'title', 'order']);

        $tabs = [
            ['key' => 'all', 'label' => 'All', 'count' => $allTop->count()],
            ['key' => 'unread', 'label' => 'Unread', 'count' => $unreadCount],
            ['key' => 'needs_reply', 'label' => 'Needs reply', 'count' => $noReplyCount],
        ];

        return view('writer.comments', array_merge($shared, compact(
            'novels', 'stats', 'tabs', 'conversations', 'activeThread', 'filter', 'chapterFilter', 'chaptersWithComments', 'sort'
        )));
    }

    public function writerPlotNotes(Request $request)
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'plot';
        $shared['breadcrumbs'] = ['Author Studio', 'Plot Notes'];
        $shared['dashboardTitle'] = 'Keep the threads of your story.';

        $selectedNovel = $novels->firstWhere('id', (int) $request->get('novel_id')) ?? $novels->first();

        $notesQuery = PlotNote::where('user_id', $user->id);
        if ($selectedNovel) {
            $notesQuery->where(function ($q) use ($selectedNovel) {
                $q->where('novel_id', $selectedNovel->id)->orWhereNull('novel_id');
            });
        }
        $allNotes = $notesQuery->latest()->get();

        $pinnedCount = $allNotes->where('is_pinned', true)->count();
        $openCount = $allNotes->where('is_open_question', true)->count();

        $tabs = [
            ['key' => 'all', 'label' => 'All notes', 'count' => $allNotes->count()],
            ['key' => 'pinned', 'label' => 'Pinned', 'count' => $pinnedCount],
            ['key' => 'open', 'label' => 'Open questions', 'count' => $openCount],
        ];

        $filter = (string) $request->get('filter', 'all');
        if (! in_array($filter, ['all', 'pinned', 'open'], true)) {
            $filter = 'all';
        }

        $visible = match ($filter) {
            'pinned' => $allNotes->where('is_pinned', true)->values(),
            'open' => $allNotes->where('is_open_question', true)->values(),
            default => $allNotes,
        };

        $notebookGroups = ['All story notes' => $allNotes->count()];
        foreach ($allNotes->groupBy(fn ($n) => $n->category_label) as $label => $group) {
            $notebookGroups[$label] = $group->count();
        }

        $activeId = (int) $request->get('note_id');
        $active = $visible->firstWhere('id', $activeId) ?? $allNotes->firstWhere('id', $activeId) ?? $visible->first();

        $recentNotes = $visible->take(12)->map(fn (PlotNote $n) => [
            'id' => $n->id,
            'title' => $n->title,
            'cat' => $n->category_label . ($n->novel ? ' · ' . $n->novel->title : ''),
            'pinned' => (bool) $n->is_pinned,
            'is_open' => (bool) $n->is_open_question,
            'active' => $active && $n->id === $active->id,
        ]);

        $activeNote = null;
        if ($active) {
            $activeNote = [
                'id' => $active->id,
                'title' => $active->title,
                'category' => $active->category,
                'tags' => $active->tags ?? [],
                'content' => $active->content ?: 'This note is still empty. Add your thoughts from the editor.',
                'is_open_question' => (bool) $active->is_open_question,
                'is_pinned' => (bool) $active->is_pinned,
                'updated' => 'UPDATED ' . $active->updated_at->diffForHumans() . ' · ' . strtoupper($user->name) . ' · PRIVATE NOTE',
                'novel_title' => $active->novel->title ?? ($selectedNovel->title ?? 'Unassigned'),
            ];
        }

        return view('writer.plot', array_merge($shared, compact(
            'novels', 'selectedNovel', 'tabs', 'filter', 'notebookGroups', 'recentNotes', 'activeNote'
        )));
    }

    private function writerSharedData(User $user)
    {
        $request = request();

        $allNovels = $user->novels()->select('id', 'title', 'status', 'view_count', 'created_at')->get();
        $allNovelIds = $allNovels->pluck('id');

        $selectedNovelId = $request->get('novel_id');
        if ($selectedNovelId && $allNovels->contains('id', (int) $selectedNovelId)) {
            $selectedNovelId = (int) $selectedNovelId;
            $scopeNovelIds = collect([$selectedNovelId]);
        } else {
            $selectedNovelId = null;
            $scopeNovelIds = $allNovelIds;
        }

        $myNovels = Novel::where('author_id', $user->id)
            ->with([
                'genres:id,name',
                'chapters' => fn ($q) => $q->select('id', 'novel_id', 'title', 'status', 'content', 'order', 'published_at', 'created_at', 'updated_at')
                    ->orderBy('order')->orderBy('id'),
            ])
            ->withCount([
                'chapters',
                'reviews',
                'chapters as published_chapters_count' => fn ($q) => $q->published(),
                'bookmarks',
            ])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        $totalWords = (int) $myNovels->sum(fn ($novel) => $novel->chapters->sum(fn ($ch) => $ch->word_count));
        $chapterCount = (int) $myNovels->sum('chapters_count');
        $publishedChapterCount = (int) $myNovels->sum('published_chapters_count');

        $totalViews = (int) $allNovels->whereIn('id', $scopeNovelIds)->sum('view_count');
        $totalBookmarks = Bookmark::whereIn('novel_id', $scopeNovelIds)->count();

        $todayStart = Carbon::today();
        $weekStart = Carbon::today()->subDays(6);
        $prevWeekStart = Carbon::today()->subDays(13);
        $prevWeekEnd = Carbon::today()->subDays(7);

        $viewsToday = (int) DB::table('novel_view_logs')->whereIn('novel_id', $scopeNovelIds)
            ->where('viewed_on', '>=', $todayStart->toDateString())->sum('views');

        $viewsLast7 = (int) DB::table('novel_view_logs')->whereIn('novel_id', $scopeNovelIds)
            ->where('viewed_on', '>=', $weekStart->toDateString())->sum('views');

        $viewsPrev7 = (int) DB::table('novel_view_logs')->whereIn('novel_id', $scopeNovelIds)
            ->whereBetween('viewed_on', [$prevWeekStart->toDateString(), $prevWeekEnd->toDateString()])
            ->sum('views');

        $newBookmarksToday = Bookmark::whereIn('novel_id', $scopeNovelIds)
            ->whereDate('created_at', '>=', $todayStart)
            ->count();

        $averageRating = round((float) Review::whereIn('novel_id', $scopeNovelIds)->avg('rating'), 2);
        $totalReviews = Review::whereIn('novel_id', $scopeNovelIds)->count();

        $unreadComments = Comment::whereHas('chapter.novel', fn ($q) => $q->where('author_id', $user->id))
            ->where('user_id', '!=', $user->id)
            ->whereNull('parent_id')
            ->whereDoesntHave('replies', fn ($q) => $q->where('user_id', $user->id))
            ->count();

        $totalComments = Comment::whereHas('chapter.novel', fn ($q) => $q->where('author_id', $user->id))->count();

        $stats = [
            'totalWords' => $totalWords,
            'chapterCount' => $chapterCount,
            'publishedChapterCount' => $publishedChapterCount,
            'avgRating' => $averageRating,
            'totalReviews' => $totalReviews,
            'unreadComments' => $unreadComments,
            'totalComments' => $totalComments,
            'viewsLast7' => $viewsLast7,
            'viewsPrev7' => $viewsPrev7,
            'viewsToday' => $viewsToday,
            'newBookmarksToday' => $newBookmarksToday,
            'totalViews' => $totalViews,
            'totalBookmarks' => $totalBookmarks,
        ];

        $readersDaily = DB::table('novel_view_logs')
            ->whereIn('novel_id', $scopeNovelIds)
            ->where('viewed_on', '>=', Carbon::now()->subDays(29)->toDateString())
            ->select('viewed_on as date', DB::raw('SUM(views) as count'))
            ->groupBy('viewed_on')
            ->pluck('count', 'date');

        $labels = [];
        $readerData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('D');
            $readerData[] = (int) $readersDaily->get($date->format('Y-m-d'), 0);
        }

        $latestReviews = Review::with(['user:id,name', 'novel:id,title,slug'])
            ->whereIn('novel_id', $allNovelIds)
            ->latest()
            ->take(10)
            ->get();

        $latestComments = Comment::with([
            'user:id,name',
            'chapter:id,novel_id,title',
            'chapter.novel:id,title,slug',
        ])
            ->whereHas('chapter', fn ($query) => $query->whereIn('novel_id', $allNovelIds))
            ->latest()
            ->take(10)
            ->get();

        $draftChapters = Chapter::with('novel:id,title,slug')
            ->whereHas('novel', fn ($query) => $query->where('author_id', $user->id))
            ->where(function ($query) {
                $query->where('status', 'draft')
                    ->orWhere('published_at', '>', now());
            })
            ->latest()
            ->take(6)
            ->get();

        $currentNovelModel = $selectedNovelId
            ? $myNovels->firstWhere('id', $selectedNovelId)
            : $myNovels->first();

        $shared = [
            'active' => 'dashboard',
            'breadcrumbs' => ['Author Studio', 'Dashboard'],
            'dashboardTitle' => 'Welcome back, ' . explode(' ', $user->name)[0] . '.',
            'dashboardSubtitle' => 'The morning is quiet. Your next page is waiting.',
            'currentNovel' => $currentNovelModel->title ?? null,
            'currentNovelId' => $currentNovelModel->id ?? null,
            'novels' => $myNovels,
            'chapterCount' => $chapterCount,
            'totalViews' => $totalViews,
            'totalBookmarks' => $totalBookmarks,
            'allNovels' => $allNovels,
            'selectedNovelId' => $selectedNovelId,
            'chartLabels' => $labels,
            'chartData' => $readerData,
            'hasNovels' => $myNovels->isNotEmpty(),
        ];

        return [$shared, $stats, $myNovels, $draftChapters, $latestComments, $latestReviews];
    }

    private function writerDashboard(User $user)
    {
        return redirect()->route('writer.dashboard');
    }

    private function readerDashboard(User $user)
    {
        $totalReadingHours = round($user->readingSessions()->sum('active_seconds') / 3600, 1);

        $lastRead = $user->readingHistories()
            ->whereHas('novel')
            ->whereHas('chapter')
            ->with(['novel', 'chapter'])
            ->latest()
            ->first();

        if ($lastRead && $lastRead->novel) {
            $totalChapters = $lastRead->novel->chapters()->count();
            $currentChapterPos = $lastRead->novel->chapters()
                ->where('id', '<=', $lastRead->chapter_id)
                ->count();

            $lastRead->progress = $totalChapters > 0 ? round(($currentChapterPos / $totalChapters) * 100) : 0;
        }

        $bookmarks = $user->bookmarks()
            ->whereHas('novel')
            ->with('novel.author')
            ->latest()
            ->take(6)
            ->get();

        $dailyGoalMinutes = 60;
        $todayReads = ReadingHistory::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $todayMinutes = min($dailyGoalMinutes, $todayReads * 15);
        $dailyGoalProgress = (int) round(($todayMinutes / $dailyGoalMinutes) * 100);

        return view('dashboard.reader', compact(
            'user',
            'totalReadingHours',
            'lastRead',
            'bookmarks',
            'dailyGoalMinutes',
            'todayMinutes',
            'dailyGoalProgress'
        ));
    }
}
