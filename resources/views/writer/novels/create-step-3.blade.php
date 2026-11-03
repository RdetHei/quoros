@extends('layouts.writer', [
    'active' => 'novels',
    'breadcrumbs' => ['Author Studio', 'My Novels', $novel->title, 'Genres & Tags'],
    'title' => 'Categories & Tags',
    'subtitle' => 'Step 3: Categorize your story so readers can find it easily.',
    'currentNovel' => $novel->title,
    'currentNovelId' => $novel->id,
    'showCreateBtn' => false,
])

@section('content')
@php
    $selectedGenres = old('genres', $novel->genres->pluck('id')->toArray());
    $selectedTags = old('tags', $novel->tags->pluck('id')->toArray());
@endphp

<div class="space-y-6">
    @include('writer.novels._wizard-steps', ['currentStep' => 3, 'novel' => $novel])

    <div class="rounded-md border border-white/5 bg-[#121212] p-6 sm:p-8" x-data="{ genreCount: {{ count($selectedGenres) }} }">
        <form action="{{ route('writer.novels.update.step-3', $novel->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <label class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Genres</label>
                        <span class="text-[10px] font-semibold uppercase tracking-[0.18em]">
                            <span :class="genreCount > 3 ? 'text-red-400' : 'text-[#c7a64a]'" x-text="genreCount"></span><span class="text-[#5f5f5f]"> / 3 selected</span>
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($genres as $genre)
                            <label class="relative cursor-pointer">
                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="peer hidden"
                                    @checked(in_array($genre->id, $selectedGenres))
                                    x-on:change="genreCount = $el.checked ? genreCount + 1 : genreCount - 1"
                                    x-bind:disabled="!$el.checked && genreCount >= 3">
                                <div class="rounded-md border border-white/10 bg-[#0a0a0a] px-3 py-2.5 text-center text-[11.5px] font-medium text-[#a3a3a3] transition-all peer-checked:border-[#c7a64a]/50 peer-checked:bg-[#c7a64a]/12 peer-checked:text-[#c7a64a] peer-disabled:cursor-not-allowed peer-disabled:opacity-30 peer-disabled:hover:border-white/10">
                                    {{ $genre->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('genres') <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <label class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Story Tags</label>
                        <span class="text-[10px] font-semibold uppercase tracking-[0.18em] text-[#5f5f5f]">Optional</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer hidden"
                                    @checked(in_array($tag->id, $selectedTags))>
                                <div class="rounded-md border border-white/10 bg-[#0a0a0a] px-3 py-1.5 text-[10px] font-medium uppercase tracking-[0.12em] text-[#a3a3a3] transition-all peer-checked:border-[#c7a64a]/50 peer-checked:bg-[#c7a64a]/12 peer-checked:text-[#c7a64a]">
                                    #{{ $tag->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('tags') <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-white/5 pt-6">
                <a href="{{ route('writer.novels.create.step-2', $novel->id) }}" class="flex items-center gap-2 rounded-md border border-white/10 px-5 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#a3a3a3] transition-all hover:border-white/20 hover:text-[#f2efe8]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Previous
                </a>
                <button type="submit" class="rounded-md bg-[#c7a64a] px-6 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">Finish & Publish</button>
            </div>
        </form>
    </div>
</div>
@endsection
