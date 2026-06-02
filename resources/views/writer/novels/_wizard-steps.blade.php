@php
    $steps = [
        1 => ['label' => 'Info Dasar', 'description' => 'Judul, tipe, bahasa, region'],
        2 => ['label' => 'Sinopsis & Cover', 'description' => 'Ringkasan cerita dan cover'],
        3 => ['label' => 'Genre & Tags', 'description' => 'Kategori dan penanda'],
    ];
@endphp

<div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
    <div class="mb-4">
        <div class="h-2 w-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
            <div
                class="h-full bg-emerald-500 transition-all"
                style="width: {{ ($currentStep / 3) * 100 }}%"
            ></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($steps as $number => $step)
            <div class="rounded-2xl border px-4 py-3 {{ $currentStep === $number ? 'border-emerald-300 bg-emerald-50 dark:bg-emerald-900/20 dark:border-emerald-800' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40' }}">
                <p class="text-[11px] font-bold uppercase tracking-wider {{ $currentStep === $number ? 'text-emerald-600 dark:text-emerald-300' : 'text-slate-400' }}">
                    Step {{ $number }}
                </p>
                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $step['label'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $step['description'] }}</p>
            </div>
        @endforeach
    </div>
</div>
