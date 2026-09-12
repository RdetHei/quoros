@php
    $steps = [
        1 => ['label' => 'Info Dasar', 'description' => 'Judul, tipe, bahasa, region'],
        2 => ['label' => 'Sinopsis & Cover', 'description' => 'Ringkasan cerita dan cover'],
        3 => ['label' => 'Genre & Tags', 'description' => 'Kategori dan penanda'],
    ];
@endphp

<div class="bg-neutral-900 rounded-xl p-5 border border-neutral-800">
    <div class="mb-4">
        <div class="h-1 w-full rounded-full bg-neutral-800 overflow-hidden">
            <div class="h-full bg-white transition-all" style="width: {{ ($currentStep / 3) * 100 }}%"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        @foreach ($steps as $number => $step)
            <div class="rounded-lg border px-4 py-3 {{ $currentStep === $number ? 'border-white bg-neutral-800' : 'border-neutral-800 bg-black/40' }}">
                <p class="text-[10px] font-medium uppercase tracking-wider {{ $currentStep === $number ? 'text-white' : 'text-neutral-600' }}">
                    Step {{ $number }}
                </p>
                <p class="mt-1 text-sm font-medium text-white">{{ $step['label'] }}</p>
                <p class="text-xs text-neutral-500">{{ $step['description'] }}</p>
            </div>
        @endforeach
    </div>
</div>
