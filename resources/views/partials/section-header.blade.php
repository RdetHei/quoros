@php
    $accentColors = [
        'indigo' => 'bg-indigo-600',
        'emerald' => 'bg-emerald-600',
        'slate' => 'bg-slate-600',
        'amber' => 'bg-amber-500',
        'rose' => 'bg-rose-500',
    ];
    $accentClass = $accentColors[$accent ?? 'indigo'] ?? $accentColors['indigo'];
@endphp

<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
    <div class="flex items-center gap-4">
        <div class="w-1 h-9 {{ $accentClass }} rounded-full shrink-0"></div>
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h2>
            @if(!empty($description))
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $description }}</p>
            @endif
        </div>
    </div>
    @if(!empty($href))
        <a href="{{ $href }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors shrink-0">
            {{ $linkText ?? 'Lihat semua' }} →
        </a>
    @endif
</div>
