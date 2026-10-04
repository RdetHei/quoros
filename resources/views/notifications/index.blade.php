@extends('layouts.app', [
    'title' => 'Notifications · Your Chronicle · Quoros',
])

@push('styles')
<style>
    .nt-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
    .nt-page {
        --nt-bg: #0a0a0a;
        --nt-surface: #121212;
        --nt-card: #131313;
        --nt-border: rgba(255, 255, 255, 0.10);
        --nt-gold: #c7a64a;
        --nt-gold-soft: #e8d39a;
        --nt-gold-pale: #ead79f;
        --nt-ink: #eeeae1;
        --nt-title: #f2efe8;
        --nt-muted: #a3a3a3;
        --nt-subtle: #737373;
        background: var(--nt-bg);
    }
    body:has(.nt-page) { background-color: var(--nt-bg) !important; }

    .nt-kicker {
        font-size: 9px;
        letter-spacing: 0.22em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--nt-gold);
    }
    .nt-kicker .sep { color: var(--nt-subtle); margin: 0 8px; }

    .nt-divider-grad {
        height: 1px;
        background: linear-gradient(to right, var(--nt-gold), transparent);
    }

    .nt-hero-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 56px;
        line-height: 1;
        letter-spacing: 0.015em;
        text-transform: uppercase;
        color: var(--nt-title);
    }
    @media (max-width: 639px) {
        .nt-hero-title { font-size: 40px; }
    }

    .nt-hero-sub {
        font-size: 12px;
        line-height: 1.7;
        color: var(--nt-muted);
        max-width: 480px;
    }

    .nt-count-num {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 32px;
        line-height: 1;
        color: var(--nt-gold);
        letter-spacing: 0.01em;
    }
    .nt-count-label {
        font-size: 8px;
        letter-spacing: 0.14em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--nt-muted);
    }

    /* Tabs */
    .nt-tab-link {
        padding: 14px 0;
        font-size: 10px;
        letter-spacing: 0.16em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--nt-muted);
        border-bottom: 2px solid transparent;
        transition: color .18s ease, border-color .18s ease;
    }
    .nt-tab-link:hover { color: #d4d4d4; }
    .nt-tab-link.is-active {
        color: var(--nt-gold);
        border-bottom-color: var(--nt-gold);
    }
    .nt-tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 16px;
        height: 14px;
        padding: 0 5px;
        border-radius: 2px;
        background: rgba(199, 166, 74, 0.15);
        color: var(--nt-gold);
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 0.05em;
        margin-left: 7px;
    }

    /* Action button */
    .nt-mark-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        border-radius: 2px;
        border: 1px solid rgba(199, 166, 74, 0.4);
        color: var(--nt-gold);
        background: transparent;
        transition: background-color .15s ease, border-color .15s ease, color .15s ease;
        white-space: nowrap;
    }
    .nt-mark-all-btn:hover {
        background: rgba(199, 166, 74, 0.08);
        border-color: var(--nt-gold);
        color: var(--nt-gold-soft);
    }

    /* Date group */
    .nt-date-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin: 28px 0 12px;
    }
    .nt-date-left {
        display: flex;
        align-items: center;
        gap: 14px;
        flex: 1;
        min-width: 0;
    }
    .nt-date-label {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 18px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #e6e1d6;
        white-space: nowrap;
    }
    .nt-date-rule {
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
        min-width: 20px;
    }
    .nt-date-count {
        font-size: 8.5px;
        letter-spacing: 0.1em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--nt-subtle);
        white-space: nowrap;
    }
    .nt-date-count .num { color: #d4d4d4; }
    .nt-date-count .lbl { color: var(--nt-subtle); margin-left: 2px; }

    /* Notification card */
    .nt-card {
        background: var(--nt-card);
        border: 1px solid var(--nt-border);
        border-radius: 6px;
        padding: 16px 18px;
        margin-bottom: 8px;
        position: relative;
        transition: border-color .2s ease, background-color .2s ease, transform .18s ease;
    }
    .nt-card.is-unread {
        background: linear-gradient(90deg, rgba(199,166,74,0.06) 0%, var(--nt-card) 40%);
        border-color: rgba(199, 166, 74, 0.25);
    }
    .nt-card:hover {
        background: #171717;
        border-color: rgba(199, 166, 74, 0.32);
        transform: translateY(-1px);
    }
    .nt-card.is-unread:hover {
        background: linear-gradient(90deg, rgba(199,166,74,0.1) 0%, #171717 45%);
    }

    .nt-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 8px;
        font-size: 7.5px;
        letter-spacing: 0.18em;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 2px;
        background: rgba(199, 166, 74, 0.12);
        color: var(--nt-gold);
        border: 1px solid rgba(199, 166, 74, 0.35);
    }
    .nt-status-pill.is-unread {
        background: rgba(199, 166, 74, 0.2);
        border-color: rgba(199, 166, 74, 0.5);
    }
    .nt-status-pill .dot {
        width: 4px;
        height: 4px;
        border-radius: 999px;
        background: currentColor;
    }

    .nt-unread-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: var(--nt-gold);
        box-shadow: 0 0 10px rgba(199,166,74,0.6);
        flex-shrink: 0;
        margin-top: 6px;
    }
    .nt-read-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        border: 1px solid #2f2f2f;
        background: transparent;
        flex-shrink: 0;
        margin-top: 6px;
    }

    .nt-notif-title {
        font-size: 13.5px;
        font-weight: 650;
        line-height: 1.3;
        letter-spacing: 0.005em;
        color: var(--nt-title);
    }
    .nt-card.is-unread .nt-notif-title { color: #f6f3ec; }

    .nt-notif-body {
        font-size: 11.5px;
        line-height: 1.65;
        color: var(--nt-muted);
        margin-top: 5px;
    }
    .nt-card.is-unread .nt-notif-body { color: #c7c2b8; }

    .nt-notif-meta {
        font-size: 8.5px;
        letter-spacing: 0.08em;
        font-weight: 550;
        text-transform: uppercase;
        color: var(--nt-subtle);
        white-space: nowrap;
    }
    .nt-notif-meta .val { color: var(--nt-muted); }

    .nt-cta-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 8.5px;
        letter-spacing: 0.16em;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--nt-gold);
        white-space: nowrap;
        transition: color .15s ease;
        margin-top: 10px;
    }
    .nt-cta-link:hover { color: var(--nt-gold-soft); }
    .nt-cta-link svg { flex-shrink: 0; }
    .nt-cta-rule {
        width: 24px;
        height: 1px;
        background: var(--nt-gold);
        opacity: .65;
    }

    /* Empty state */
    .nt-empty {
        border-radius: 6px;
        border: 1px solid var(--nt-border);
        background: #111111;
        padding: 56px 20px;
        text-align: center;
    }
    .nt-empty-icon {
        margin: 0 auto 18px;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        border: 1px solid rgba(199,166,74,0.25);
        background: rgba(199,166,74,0.06);
        color: var(--nt-gold);
    }
    .nt-empty-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 22px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        color: var(--nt-title);
    }
    .nt-empty-sub {
        margin: 6px auto 0;
        font-size: 11.5px;
        line-height: 1.7;
        color: var(--nt-muted);
        max-width: 340px;
    }

    /* Footer */
    .nt-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        background: #080808;
        margin-top: 56px;
    }
    .nt-foot-brand {
        font-size: 15px;
        letter-spacing: 0.28em;
        font-weight: 600;
        color: var(--nt-title);
    }
    .nt-foot-tagline {
        font-size: 10.5px;
        color: var(--nt-subtle);
        line-height: 1.6;
        margin-top: 8px;
        max-width: 320px;
    }
    .nt-foot-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 22px 28px;
        justify-content: flex-end;
    }
    .nt-foot-nav a {
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--nt-muted);
        transition: color .15s ease;
    }
    .nt-foot-nav a:hover { color: var(--nt-gold); }

    .nt-foot-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        font-size: 8.5px;
        letter-spacing: 0.08em;
        color: var(--nt-subtle);
    }
    .nt-foot-bottom .links a { color: var(--nt-muted); transition: color .15s ease; }
    .nt-foot-bottom .links a:hover { color: var(--nt-gold); }
    .nt-foot-bottom .links .sep { margin: 0 7px; color: #2e2e2e; }

    @media (prefers-reduced-motion: reduce) {
        .nt-card, .nt-mark-all-btn, .nt-tab-link, .nt-cta-link { transition: none !important; }
    }
</style>
@endpush

@section('content')
<main class="nt-page min-h-screen w-full overflow-x-hidden pt-24 sm:pt-28 pb-12">
    <div class="mx-auto w-full max-w-[1100px] px-5 sm:px-7 lg:px-10">

        {{-- ─── HERO ────────────────────────────────────────────────────── --}}
        <header class="mb-8 border-b border-white/[.08] pb-9 sm:mb-10 sm:pb-10">
            <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end sm:gap-8">
                <div class="min-w-0 max-w-2xl">
                    <p class="mb-3 nt-kicker">
                        Your Chronicle <span class="sep">/</span> Messenger
                    </p>
                    <h1 class="nt-hero-title">Notifications</h1>
                    <p class="mt-4 nt-hero-sub">
                        Every reply, new chapter, and archive announcement kept in one ledger.
                    </p>
                    <div class="mt-6 nt-divider-grad w-56"></div>
                </div>
                @php
                    $totalNt = $notifications->total();
                    $unreadNt = $notifications->getCollection()->where(fn($n)=>$n->isUnread())->count();
                    if ($unreadNt === 0) $unreadNt = \App\Models\InAppNotification::where('user_id', auth()->id())->whereNull('read_at')->count();
                @endphp
                <div class="flex shrink-0 flex-col items-end justify-end gap-1">
                    <div class="nt-count-num">{{ str_pad((string) max(0, (int) $unreadNt), 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="nt-count-label">Unread dispatches</div>
                </div>
            </div>

            {{-- Tabs + read all --}}
            <div class="mt-9 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:gap-8">
                <nav class="flex flex-wrap items-center gap-x-8 sm:gap-x-10" role="tablist" aria-label="Notification lists">
                    @php
                        $ntTabs = [
                            ['id' => 'all', 'label' => 'All', 'count' => (int) $totalNt, 'active' => true],
                            ['id' => 'unread', 'label' => 'Unread', 'count' => max(0, (int) $unreadNt), 'active' => false],
                            ['id' => 'announcements', 'label' => 'Announcements', 'count' => 0, 'active' => false],
                        ];
                    @endphp
                    @foreach($ntTabs as $t)
                        <a href="#" role="tab" aria-selected="{{ $t['active'] ? 'true' : 'false' }}"
                           class="nt-tab-link @if($t['active']) is-active @endif">
                            {{ $t['label'] }}
                            <span class="nt-tab-count">{{ $t['count'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="shrink-0 flex items-center gap-2">
                    @if(($hasUnread ?? false) || $unreadNt > 0)
                        <form action="{{ route('notifications.read-all') }}" method="POST">
                            @csrf
                            <button type="submit" class="nt-mark-all-btn">
                                <svg viewBox="0 0 24 24" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                Mark all as read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </header>

        {{-- ─── INFO ROW ────────────────────────────────────────────────── --}}
        <div class="mb-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="text-[9px] uppercase tracking-[.12em] text-neutral-500 font-semibold">
                Showing <span style="color:#d4d4d4;">{{ $notifications->count() ? '1–'.$notifications->count() : '0' }}</span> of <span style="color:#d4d4d4;">{{ $totalNt }}</span> entries
            </div>
            <div class="text-[9px] uppercase tracking-[.12em] text-neutral-500 font-semibold sm:text-right">
                Most recent first <span style="color:#525252;margin-left:2px;">↓</span>
            </div>
        </div>

        {{-- ─── GROUPED NOTIFICATIONS ──────────────────────────────────── --}}
        @php
            $groupOrder = ['today','yesterday','monday','tuesday','wednesday','thursday','friday','saturday','sunday','archive'];
            $labelMap  = [
                'today'=>'TODAY','yesterday'=>'YESTERDAY','monday'=>'MONDAY','tuesday'=>'TUESDAY',
                'wednesday'=>'WEDNESDAY','thursday'=>'THURSDAY','friday'=>'FRIDAY','saturday'=>'SATURDAY',
                'sunday'=>'SUNDAY','archive'=>'ARCHIVE',
            ];
            $ntGroups = [];
            foreach ($notifications as $n) {
                if ($n->created_at->isToday()) $key = 'today';
                elseif ($n->created_at->isYesterday()) $key = 'yesterday';
                elseif ($n->created_at->diffInDays(now()) < 7) $key = strtolower($n->created_at->format('l'));
                else $key = 'archive';
                if (!isset($ntGroups[$key])) {
                    $ntGroups[$key] = [
                        'label' => $labelMap[$key] ?? 'EARLIER',
                        'date'  => $n->created_at,
                        'items' => [],
                    ];
                }
                $ntGroups[$key]['items'][] = $n;
            }
            uksort($ntGroups, function($a, $b) use ($groupOrder) {
                $pa = array_search($a, $groupOrder, true); if ($pa === false) $pa = 999;
                $pb = array_search($b, $groupOrder, true); if ($pb === false) $pb = 999;
                return $pa <=> $pb;
            });
        @endphp

        @if($notifications->isNotEmpty())

            @foreach($ntGroups as $gKey => $g)
                <div class="nt-date-group">
                    <div class="nt-date-left">
                        <span class="nt-date-label">{{ $g['label'] }}</span>
                        <span style="font-size:8.5px;letter-spacing:.1em;color:var(--nt-subtle);font-weight:550;text-transform:uppercase;white-space:nowrap;">
                            {{ strtoupper($g['date']->format('j F Y')) }}
                        </span>
                        <div class="nt-date-rule"></div>
                    </div>
                    <span class="nt-date-count">
                        <span class="num">{{ str_pad((string) count($g['items']), 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="lbl">ENTRIES</span>
                    </span>
                </div>

                <div class="space-y-2">
                    @foreach($g['items'] as $n)
                        @php
                            $isUnread = $n->isUnread();
                            $nType = $n->type->value ?? 'general';
                            $nLabel = match(strtolower($nType)) {
                                'announcement' => 'Announcement',
                                'comment' => 'Comment',
                                'reply' => 'Reply',
                                'review' => 'Review',
                                'follow' => 'New Follower',
                                'bookmark' => 'Bookmark',
                                'chapter' => 'New Chapter',
                                default => ucfirst($nType),
                            };
                        @endphp
                        <a href="{{ route('notifications.show', $n) }}" class="nt-card block @if($isUnread) is-unread @endif">
                            <div class="flex items-start gap-4">
                                {{ $isUnread ? '<span class="nt-unread-dot"></span>' : '<span class="nt-read-dot"></span>' }}

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="nt-status-pill @if($isUnread) is-unread @endif">
                                            <span class="dot"></span>
                                            {{ $nLabel }}
                                        </span>
                                        @if($isUnread)
                                            <span style="font-size:7.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--nt-gold);font-weight:650;">
                                                Unread
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="nt-notif-title line-clamp-2">{{ $n->title() }}</h3>
                                            <p class="nt-notif-body line-clamp-3">{{ $n->body() }}</p>

                                            @if($n->url())
                                                <div class="nt-cta-link">
                                                    <span>View source</span>
                                                    <span class="nt-cta-rule"></span>
                                                    <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="shrink-0 mt-3 sm:mt-0 sm:text-right">
                                            <div class="nt-notif-meta">
                                                <span class="val">{{ $n->created_at->diffForHumans(null, true) }}</span>
                                            </div>
                                            <div class="nt-notif-meta mt-1">
                                                {{ $n->created_at->format('j M, H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endforeach
        @else
            <div class="nt-empty">
                <div class="nt-empty-icon">
                    <svg viewBox="0 0 24 24" class="h-5.5 w-5.5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                </div>
                <h2 class="nt-empty-title">Inbox is at peace</h2>
                <p class="nt-empty-sub">No dispatches yet. New chapters, replies, and archive news will land here.</p>
            </div>
        @endif

        {{-- ─── PAGINATION ─────────────────────────────────────────────── --}}
        @if($notifications->hasPages())
            <div class="mt-10">
                {{ $notifications->onEachSide(1)->links('vendor.pagination.tailwind') }}
            </div>
        @endif

    </div>
</main>
@endsection
