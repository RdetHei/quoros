@extends('layouts.writer', [
    'active' => $active ?? 'earnings',
])

@section('dashboard-content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        @foreach(['September 2026' => true, 'This year' => false, 'All time' => false] as $label => $active)
            <button class="rounded-sm border {{ $active ? 'border-[#c7a64a]/40 bg-[#c7a64a]/12 text-[#c7a64a]' : 'border-white/5 bg-[#121212] text-[#a3a3a3] hover:text-[#f2efe8] hover:border-white/10' }} px-3.5 py-1.5 text-[11px] font-semibold tracking-wide transition-all">
                {{ $label }}
            </button>
        @endforeach
    </div>
    <button class="inline-flex items-center gap-2 rounded-md border border-white/10 bg-[#121212] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.15em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
        Download Statement
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 mb-6">
    @foreach($periodCards as $card)
    <div class="rounded-md border border-white/5 bg-[#121212] p-4.5 hover:border-[#c7a64a]/20 transition-all">
        <p class="text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3] mb-2.5">{{ $card['label'] }}</p>
        <p class="text-[30px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight">{{ $card['value'] }}</p>
        @if(!empty($card['delta']))
            <p class="mt-2.5 text-[11px] font-semibold text-[#c7a64a] tracking-wide">{{ $card['delta'] }}</p>
        @endif
        @if(!empty($card['sub']))
            <p class="mt-2.5 text-[11px] text-[#a3a3a3]">{{ $card['sub'] }}</p>
        @endif
    </div>
    @endforeach
</div>

<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
    <div class="space-y-6 min-w-0">

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="mb-5">
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">Monthly Net Earnings</p>
                <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">Small chapters. Lasting support.</h3>
            </div>

            <div class="h-[230px] relative px-3 pt-2">
                @php
                    $months = ['Apr' => 610, 'May' => 780, 'Jun' => 920, 'Jul' => 995, 'Aug' => 1074, 'Sep' => 1248];
                    $max = 1248;
                    $barW = 100 / 6;
                @endphp
                <div class="relative h-full flex items-end gap-4 px-2">
                    @foreach($months as $month => $val)
                        @php
                            $h = ($val / $max) * 85;
                            $isLast = $month === 'Sep';
                        @endphp
                        <div class="flex-1 flex flex-col items-center justify-end h-full relative group">
                            <div class="text-[10px] font-semibold {{ $isLast ? 'text-[#c7a64a]' : 'text-[#a3a3a3]' }} absolute -top-1 tabular-nums">
                                ${{ number_format($val) }}
                            </div>
                            <div class="w-full max-w-[72px] rounded-t-sm {{ $isLast ? 'bg-[#c7a64a]' : 'bg-[#c7a64a]/25' }} transition-all hover:opacity-90" style="height: {{ $h }}%"></div>
                            <div class="mt-3 text-[10px] font-medium uppercase tracking-[0.2em] {{ $isLast ? 'text-[#f2efe8]' : 'text-[#a3a3a3]' }}">{{ $month }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="absolute left-0 right-0 bottom-[32px] border-t border-white/5"></div>
            </div>
            <p class="mt-5 text-[11px] text-[#a3a3a3]">All amounts in USD. Net earnings after platform fees.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @foreach($revenueBreakdown as $rev)
            <div class="rounded-md border border-white/5 bg-[#121212] p-4.5 hover:border-[#c7a64a]/20 transition-all">
                <div class="flex items-center gap-2.5 mb-3">
                    <span class="text-[#c7a64a]">
                        @if($rev['icon'] === 'bookmark')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                        @elseif($rev['icon'] === 'heart')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" /></svg>
                        @endif
                    </span>
                    <p class="text-[13px] font-medium text-[#f2efe8]">{{ $rev['title'] }}</p>
                </div>
                <p class="text-[24px] font-serif font-medium text-[#f2efe8] leading-none mb-2.5 tracking-tight">{{ $rev['value'] }}</p>
                <p class="text-[10.5px] text-[#a3a3a3]">{{ $rev['sub'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-1.5">September Statement</p>
                    <h3 class="text-[22px] font-serif font-medium text-[#f2efe8] tracking-tight">Transaction history</h3>
                </div>
                <a href="#" class="text-[11px] font-semibold uppercase tracking-[0.15em] text-[#c7a64a] hover:text-[#d4b55a] inline-flex items-center gap-1.5">
                    All Transactions
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            <div class="overflow-hidden rounded-md border border-white/5">
                <div class="grid grid-cols-12 bg-[#0a0a0a] px-4 py-3 text-[9.5px] font-semibold uppercase tracking-[0.18em] text-[#a3a3a3]">
                    <div class="col-span-4">Transaction</div>
                    <div class="col-span-3">Period / Date</div>
                    <div class="col-span-3">Source</div>
                    <div class="col-span-1 text-right">Net Amount</div>
                    <div class="col-span-1 text-right">Status</div>
                </div>
                @foreach($transactions as $tx)
                <div class="grid grid-cols-12 items-center px-4 py-4 border-t border-white/5 hover:bg-white/[0.015] transition-colors">
                    <div class="col-span-4 text-[13px] text-[#f2efe8] font-medium">{{ $tx['name'] }}</div>
                    <div class="col-span-3 text-[11.5px] text-[#a3a3a3] tabular-nums">{{ $tx['period'] }}</div>
                    <div class="col-span-3 text-[11.5px] text-[#a3a3a3] truncate">{{ $tx['source'] }}</div>
                    <div class="col-span-1 text-right text-[12px] text-[#f2efe8] font-semibold tabular-nums">{{ $tx['amount'] }}</div>
                    <div class="col-span-1 text-right">
                        <span class="inline-flex items-center text-[10.5px] font-semibold tracking-wide {{ $tx['status'] === 'Paid' ? 'text-[#f2efe8]' : ($tx['status'] === 'Pending' ? 'text-amber-300' : 'text-[#c7a64a]') }}">
                            {{ $tx['status'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <aside class="space-y-6 min-w-0">
        <div class="rounded-md border border-white/5 bg-[#121212] p-5">
            <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-2">Payout Status</p>
            <h3 class="text-[18px] font-serif font-medium text-[#f2efe8] tracking-tight mb-3.5">Your next payout</h3>

            <div class="flex items-start justify-between mb-2 gap-3">
                <p class="text-[36px] font-serif font-medium text-[#f2efe8] leading-none tracking-tight">$960.00</p>
                <span class="shrink-0 mt-1 rounded border border-[#c7a64a]/30 px-2.5 py-1 text-[9.5px] font-bold uppercase tracking-[0.15em] text-[#c7a64a]">Scheduled</span>
            </div>
            <p class="text-[12.5px] text-[#a3a3a3] mb-5">Arriving October 5, 2026</p>

            <div class="border-t border-white/5 pt-4 mb-4">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 text-[#c7a64a]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-medium text-[#f2efe8]">Bank account · •••• 4821</p>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mt-0.5 font-semibold">{{ auth()->user()->name }} · USD</p>
                    </div>
                </div>
                <p class="mt-3.5 text-[11px] text-[#a3a3a3]">Automatic monthly payouts are enabled. Minimum payout: $50.</p>
            </div>

            <div class="flex flex-wrap gap-2.5">
                <button class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543-.94-3.31.826-2.37 2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Payout Settings
                </button>
                <button class="inline-flex items-center gap-2 rounded-md border border-white/10 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#f2efe8] transition-all hover:border-white/20 hover:bg-white/5">
                    View History
                </button>
            </div>
        </div>
    </aside>
</div>

@endsection
