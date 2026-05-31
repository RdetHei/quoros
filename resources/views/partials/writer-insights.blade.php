@if($writerStats)
<section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Writer Insights</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ringkasan performa karya penulis.</p>
        </div>
        @if($isOwner ?? false)
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('writer.stats') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors">
                Statistik Detail
            </a>
            <a href="{{ route('writer.novels.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                Kelola Novel
            </a>
        </div>
        @endif
    </div>
    <div class="p-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Total Views</p>
            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ number_format($writerStats['total_views']) }}</p>
        </div>
        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Total Ulasan</p>
            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ number_format($writerStats['total_comments']) }}</p>
        </div>
        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Rating Rata-rata</p>
            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ number_format($writerStats['avg_rating'], 1) }}</p>
        </div>
        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Total Karya</p>
            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ number_format($writerStats['novel_count']) }}</p>
        </div>
    </div>
</section>
@endif
