@extends('layouts.writer', [
    'title' => 'Novel Identity',
    'subtitle' => 'Step 1: Define the core details of your story.'
])

@section('content')
<div class="space-y-6">
    @include('writer.novels._wizard-steps', ['currentStep' => 1])

    <div class="bg-neutral-900 rounded-xl p-6 border border-neutral-800 relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div class="shrink-0 p-3 bg-white rounded-lg text-black">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-white uppercase tracking-wide">Submission Protocol & Guidelines</h3>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <p class="text-xs leading-relaxed text-neutral-400">
                        <strong class="text-white">Manual Review:</strong> All new works undergo a thorough review by the Quoros Team to ensure catalog quality and safety.
                    </p>
                    <p class="text-xs leading-relaxed text-neutral-400">
                        <strong class="text-white">Strict Prohibitions:</strong> Sexually explicit imagery, pornographic content, and privacy violations will result in immediate rejection and account suspension.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-neutral-900 rounded-xl p-8 border border-neutral-800">
        <form action="{{ route('writer.novels.store.step-1') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach([
                    ['name' => 'title', 'label' => 'Novel Title', 'type' => 'text', 'required' => true, 'placeholder' => 'Enter a catchy title...'],
                    ['name' => 'alternative_title', 'label' => 'Alternative Title', 'type' => 'text', 'required' => false, 'placeholder' => 'Optional...'],
                ] as $field)
                    <div class="space-y-2">
                        <label for="{{ $field['name'] }}" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">{{ $field['label'] }}</label>
                        <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="{{ $field['name'] }}" value="{{ old($field['name'], $novel->{$field['name']} ?? '') }}"
                            class="w-full px-4 py-3 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-white placeholder:text-neutral-600"
                            placeholder="{{ $field['placeholder'] }}" @if($field['required']) required @endif>
                        @error($field['name']) <p class="text-neutral-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @endforeach

                <div class="space-y-2">
                    <label for="type" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Content Type</label>
                    <select name="type" id="type" class="w-full px-4 py-3 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-white" required>
                        <option value="original" {{ old('type', $novel->type ?? '') === 'original' ? 'selected' : '' }}>Original Story</option>
                        <option value="web_novel" {{ old('type', $novel->type ?? '') === 'web_novel' ? 'selected' : '' }}>Web Novel</option>
                        <option value="light_novel" {{ old('type', $novel->type ?? '') === 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                    </select>
                    @error('type') <p class="text-neutral-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="status" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-3 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-white" required>
                        <option value="ongoing" {{ old('status', $novel->status ?? '') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="hiatus" {{ old('status', $novel->status ?? '') === 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                        <option value="complete" {{ old('status', $novel->status ?? '') === 'complete' ? 'selected' : '' }}>Complete</option>
                    </select>
                    @error('status') <p class="text-neutral-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="content_rating" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Content Rating</label>
                    <select name="content_rating" id="content_rating" class="w-full px-4 py-3 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-white" required>
                        <option value="everyone" {{ old('content_rating', $novel->content_rating ?? '') === 'everyone' ? 'selected' : '' }}>Everyone</option>
                        <option value="teen" {{ old('content_rating', $novel->content_rating ?? '') === 'teen' ? 'selected' : '' }}>Teen (13+)</option>
                        <option value="mature" {{ old('content_rating', $novel->content_rating ?? '') === 'mature' ? 'selected' : '' }}>Mature (18+)</option>
                    </select>
                    @error('content_rating') <p class="text-neutral-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="language" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Language</label>
                    <select name="language" id="language" class="w-full px-4 py-3 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-white" required>
                        <option value="id" {{ old('language', $novel->language ?? '') === 'id' ? 'selected' : '' }}>Indonesian</option>
                        <option value="en" {{ old('language', $novel->language ?? '') === 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="region" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Content Region</label>
                    <select name="region" id="region" class="w-full px-4 py-3 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-white" required>
                        <option value="lokal" {{ old('region', $novel->region ?? '') === 'lokal' ? 'selected' : '' }}>Lokal</option>
                        <option value="global" {{ old('region', $novel->region ?? '') === 'global' ? 'selected' : '' }}>Global</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-neutral-800">
                <a href="{{ route('dashboard', ['tab' => 'library']) }}" class="px-6 py-3 text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all">Next Step</button>
            </div>
        </form>
    </div>
</div>
@endsection
