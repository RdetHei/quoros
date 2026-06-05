@extends('layouts.writer', [
    'title' => 'Categories & Tags',
    'subtitle' => 'Step 3: Categorize your story so readers can find it easily.'
])

@section('content')
<div class="space-y-8">
    @include('writer.novels._wizard-steps', ['currentStep' => 3])

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-sm">
        <form action="{{ route('writer.novels.update.step-3', $novel->id) }}" method="POST" class="space-y-10">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Genres Area -->
                <div class="space-y-4">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Genres (Select up to 3)</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($genres as $genre)
                            <label class="relative cursor-pointer">
                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="peer hidden" 
                                    {{ in_array($genre->id, old('genres', $novel->genres->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-bold text-slate-500 dark:text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all text-center">
                                    {{ $genre->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('genres') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Tags Area -->
                <div class="space-y-4">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Story Tags (Select relevant tags)</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer hidden"
                                    {{ in_array($tag->id, old('tags', $novel->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500 transition-all">
                                    #{{ $tag->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('tags') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-10 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('writer.novels.create.step-2', $novel->id) }}" class="px-8 py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Previous
                </a>
                <button type="submit" class="px-10 py-4 bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-600/20">
                    Finish & Publish
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
