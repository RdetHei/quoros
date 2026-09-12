@extends('layouts.writer', [
    'title' => 'Categories & Tags',
    'subtitle' => 'Step 3: Categorize your story so readers can find it easily.'
])

@section('content')
<div class="space-y-6">
    @include('writer.novels._wizard-steps', ['currentStep' => 3])

    <div class="bg-neutral-900 rounded-xl p-8 border border-neutral-800">
        <form action="{{ route('writer.novels.update.step-3', $novel->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Genres (Select up to 3)</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($genres as $genre)
                            <label class="relative cursor-pointer">
                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="peer hidden"
                                    {{ in_array($genre->id, old('genres', $novel->genres->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <div class="px-3 py-2.5 bg-black border border-neutral-700 rounded-lg text-xs font-medium text-neutral-400 peer-checked:bg-white peer-checked:text-black peer-checked:border-white transition-all text-center">
                                    {{ $genre->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('genres') <p class="text-neutral-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <label class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Story Tags</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer hidden"
                                    {{ in_array($tag->id, old('tags', $novel->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <div class="px-3 py-1.5 bg-black border border-neutral-700 rounded-md text-[10px] font-medium uppercase tracking-wider text-neutral-500 peer-checked:bg-white peer-checked:text-black peer-checked:border-white transition-all">
                                    #{{ $tag->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('tags') <p class="text-neutral-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-neutral-800">
                <a href="{{ route('writer.novels.create.step-2', $novel->id) }}" class="px-6 py-3 text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-white transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Previous
                </a>
                <button type="submit" class="px-8 py-3 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all">Finish & Publish</button>
            </div>
        </form>
    </div>
</div>
@endsection
