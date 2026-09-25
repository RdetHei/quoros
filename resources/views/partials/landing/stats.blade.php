<div class="stat-ticker" style="border-top:1px solid #262626; border-bottom:1px solid #262626; background:#0a0a0a; overflow:hidden; padding:0.7rem 0;">
    <div class="stat-ticker-track">
        @php
            $tickerItems = [
                ['val' => number_format($stats['novels']),       'lbl' => 'novel tersedia'],
                ['val' => number_format($stats['chapters']),     'lbl' => 'chapter diterbitkan'],
                ['val' => number_format($stats['genres']),       'lbl' => 'genre beragam'],
                ['val' => number_format($stats['updates_week']), 'lbl' => 'update minggu ini'],
            ];
            $tickerItems = array_merge($tickerItems, $tickerItems, $tickerItems, $tickerItems);
        @endphp
        @foreach($tickerItems as $t)
        <div class="flex items-center shrink-0" style="padding:0 2.25rem;">
            <span class="text-[15px] font-black tabular-nums" style="color:#f5f5f5;">{{ $t['val'] }}</span>
            <span class="text-xs font-medium ml-1.5" style="color:#737373;">{{ $t['lbl'] }}</span>
            <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:#737373;margin-left:2.25rem;"></span>
        </div>
        @endforeach
    </div>
</div>
