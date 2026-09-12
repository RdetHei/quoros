@if($writerStats)
<section class="bg-neutral-900 rounded-xl border border-neutral-800 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-neutral-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-base font-medium text-white">Writer Insights</h2>
            <p class="text-xs text-neutral-500 mt-0.5">Summary of author's work performance.</p>
        </div>
        @if($isOwner ?? false)
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('dashboard', ['tab' => 'analytics']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-black bg-white rounded-md hover:bg-neutral-200 transition-colors">
                Detailed Statistics
            </a>
            <a href="{{ route('dashboard', ['tab' => 'library']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-neutral-300 bg-neutral-800 border border-neutral-700 rounded-md hover:bg-neutral-700 hover:text-white transition-colors">
                Manage Novels
            </a>
        </div>
        @endif
    </div>
    <div class="p-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total Views', 'value' => number_format($writerStats['total_views'])],
            ['label' => 'Total Reviews', 'value' => number_format($writerStats['total_comments'])],
            ['label' => 'Average Rating', 'value' => number_format($writerStats['avg_rating'], 1)],
            ['label' => 'Total Works', 'value' => number_format($writerStats['novel_count'])],
        ] as $stat)
            <div class="p-4 rounded-lg bg-black/40 border border-neutral-800">
                <p class="text-xs font-medium text-neutral-500 mb-1">{{ $stat['label'] }}</p>
                <p class="text-xl font-semibold text-white tabular-nums">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>
</section>
@endif
