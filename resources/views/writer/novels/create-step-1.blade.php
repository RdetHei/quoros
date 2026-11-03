@extends('layouts.writer', [
    'active' => 'novels',
    'breadcrumbs' => ['Author Studio', 'My Novels', ($novel ?? null) ? 'Edit Novel' : 'Create Novel'],
    'title' => ($novel ?? null) ? 'Edit Novel Identity' : 'Novel Identity',
    'subtitle' => 'Step 1: Define the core details of your story.',
    'showCreateBtn' => false,
])

@section('content')
@php
    $inputClass = 'w-full rounded-md border border-white/10 bg-[#0a0a0a] px-4 py-3 text-[13px] text-[#f2efe8] placeholder:text-[#5f5f5f] transition-all focus:border-[#c7a64a]/50 focus:outline-none focus:ring-1 focus:ring-[#c7a64a]/40';
    $labelClass = 'text-[10px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]';
    $selectClass = $inputClass . ' appearance-none pr-10';
@endphp

<div class="space-y-6">
    @include('writer.novels._wizard-steps', ['currentStep' => 1, 'novel' => $novel])

    <div class="relative overflow-hidden rounded-md border border-[#c7a64a]/20 bg-[#121212] p-6">
        <div class="flex flex-col gap-6 md:flex-row md:items-center">
            <div class="shrink-0 rounded-md border border-[#c7a64a]/30 bg-[#c7a64a]/10 p-3 text-[#c7a64a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
                <h3 class="text-[12px] font-semibold uppercase tracking-[0.16em] text-[#c7a64a]">Submission Protocol & Guidelines</h3>
                <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <p class="text-[12px] leading-relaxed text-[#a3a3a3]">
                        <strong class="text-[#f2efe8]">Manual Review:</strong> All new works undergo a thorough review by the Quoros Team to ensure catalog quality and safety.
                    </p>
                    <p class="text-[12px] leading-relaxed text-[#a3a3a3]">
                        <strong class="text-[#f2efe8]">Strict Prohibitions:</strong> Sexually explicit imagery, pornographic content, and privacy violations will result in immediate rejection and account suspension.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-md border border-white/5 bg-[#121212] p-6 sm:p-8">
        <form action="{{ route('writer.novels.store.step-1') }}" method="POST" class="space-y-6">
            @csrf
            @if($novel)
                <input type="hidden" name="novel_id" value="{{ $novel->id }}">
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @foreach([
                    ['name' => 'title', 'label' => 'Novel Title', 'required' => true, 'placeholder' => 'Enter a catchy title...'],
                    ['name' => 'alternative_title', 'label' => 'Alternative Title', 'required' => false, 'placeholder' => 'Optional...'],
                ] as $field)
                    <div class="space-y-2">
                        <label for="{{ $field['name'] }}" class="{{ $labelClass }}">{{ $field['label'] }}</label>
                        <input type="text" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                            value="{{ old($field['name'], $novel->{$field['name']} ?? '') }}"
                            class="{{ $inputClass }}"
                            placeholder="{{ $field['placeholder'] }}" @if($field['required']) required @endif>
                        @error($field['name']) <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                    </div>
                @endforeach

                @foreach([
                    ['name' => 'type', 'label' => 'Content Type', 'options' => ['original' => 'Original Story', 'web_novel' => 'Web Novel', 'light_novel' => 'Light Novel']],
                    ['name' => 'status', 'label' => 'Status', 'options' => ['ongoing' => 'Ongoing', 'hiatus' => 'Hiatus', 'complete' => 'Complete']],
                    ['name' => 'content_rating', 'label' => 'Content Rating', 'options' => ['everyone' => 'Everyone', 'teen' => 'Teen (13+)', 'mature' => 'Mature (18+)']],
                    ['name' => 'language', 'label' => 'Language', 'options' => ['id' => 'Indonesian', 'en' => 'English']],
                    ['name' => 'region', 'label' => 'Content Region', 'options' => ['lokal' => 'Lokal', 'global' => 'Global']],
                ] as $field)
                    <div class="space-y-2">
                        <label for="{{ $field['name'] }}" class="{{ $labelClass }}">{{ $field['label'] }}</label>
                        <div class="relative">
                            <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="{{ $selectClass }}" required>
                                @foreach($field['options'] as $value => $text)
                                    <option value="{{ $value }}" {{ old($field['name'], $novel->{$field['name']} ?? '') === $value ? 'selected' : '' }}>{{ $text }}</option>
                                @endforeach
                            </select>
                            <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        @error($field['name']) <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-white/5 pt-6">
                <a href="{{ route('writer.novels') }}" class="rounded-md border border-white/10 px-5 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#a3a3a3] transition-all hover:border-white/20 hover:text-[#f2efe8]">Cancel</a>
                <button type="submit" class="rounded-md bg-[#c7a64a] px-6 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">Next Step</button>
            </div>
        </form>
    </div>
</div>
@endsection
