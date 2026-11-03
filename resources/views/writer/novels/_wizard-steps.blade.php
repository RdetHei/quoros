@php
    $currentStep = $currentStep ?? 1;
    $novel = $novel ?? null;

    $steps = [
        1 => ['label' => 'Novel Identity', 'description' => 'Title, type, language & region'],
        2 => ['label' => 'Synopsis & Cover', 'description' => 'Story summary and artwork'],
        3 => ['label' => 'Genres & Tags', 'description' => 'Categories and story markers'],
    ];

    $stepUrl = function (int $number) use ($novel) {
        return match ($number) {
            1 => route('writer.novels.create.step-1', $novel ? ['novel' => $novel->id] : []),
            2 => $novel ? route('writer.novels.create.step-2', $novel) : null,
            3 => $novel ? route('writer.novels.create.step-3', $novel) : null,
            default => null,
        };
    };
@endphp

<div class="rounded-md border border-white/5 bg-[#121212] p-5">
    <div class="mb-4 flex items-center justify-between gap-3">
        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Creation Progress</p>
        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a]">Step {{ $currentStep }} / 3</p>
    </div>

    <div class="mb-5 h-1 w-full overflow-hidden rounded-full bg-white/5">
        <div class="h-full rounded-full bg-[#c7a64a] transition-all duration-500" style="width: {{ ($currentStep / 3) * 100 }}%"></div>
    </div>

    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
        @foreach ($steps as $number => $step)
            @php
                $isCurrent = $currentStep === $number;
                $isDone = $number < $currentStep;
                $url = $stepUrl($number);
                $clickable = $url && ($isDone || $isCurrent);
                $tag = $clickable ? 'a' : 'div';
                $cardClass = $isCurrent
                    ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10'
                    : ($isDone ? 'border-white/10 bg-[#0a0a0a] hover:border-[#c7a64a]/30' : 'border-white/5 bg-[#0a0a0a]/40');
                $badgeClass = $isCurrent
                    ? 'border-[#c7a64a]/50 bg-[#c7a64a] text-black'
                    : ($isDone ? 'border-[#c7a64a]/40 text-[#c7a64a]' : 'border-white/10 text-[#5f5f5f]');
            @endphp

            <{{ $tag }} @if($clickable) href="{{ $url }}" @endif
                class="flex items-start gap-3 rounded-md border px-4 py-3 transition-all {{ $cardClass }}">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border text-[10px] font-semibold {{ $badgeClass }}">
                    @if($isDone)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        {{ $number }}
                    @endif
                </span>
                <span class="min-w-0">
                    <span class="block text-[12px] font-semibold {{ $isCurrent || $isDone ? 'text-[#f2efe8]' : 'text-[#a3a3a3]' }}">{{ $step['label'] }}</span>
                    <span class="block truncate text-[10.5px] text-[#5f5f5f]">{{ $step['description'] }}</span>
                </span>
            </{{ $tag }}>
        @endforeach
    </div>
</div>
