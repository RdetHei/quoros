@php
    $isEdit = isset($character);
@endphp

<form
    action="{{ $isEdit ? route('writer.novels.characters.update', [$novel, $character]) : route('writer.novels.characters.store', $novel) }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-6"
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Character Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $character->name ?? '') }}" placeholder="e.g. Arthur Leywin" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    @error('name') <p class="mt-2 text-xs text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="role" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Story Role</label>
                    <input type="text" name="role" id="role" value="{{ old('role', $character->role ?? '') }}" placeholder="e.g. Protagonist, Antagonist, Supporting" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>
            </div>

            <div>
                <label class="text-xs font-black uppercase tracking-widest text-slate-400">Character Image</label>
                <div class="relative group aspect-square rounded-3xl overflow-hidden bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 shadow-inner">
                    @if(isset($character) && $character->image)
                        <div id="character-placeholder" class="hidden"></div>
                        <img src="{{ asset('storage/' . $character->image) }}" id="character-preview" class="w-full h-full object-cover">
                    @else
                        <div id="character-placeholder" class="flex flex-col items-center justify-center h-full text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <p class="text-[10px] font-black uppercase tracking-widest">Optional</p>
                        </div>
                        <img src="" id="character-preview" class="hidden w-full h-full object-cover">
                    @endif
                    <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 flex items-center justify-center">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="initCropper(this, 'character-preview', {aspectRatio: 1, placeholderId: 'character-placeholder'})">
                        <span class="px-4 py-2 bg-white text-slate-900 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg">Change Image</span>
                    </label>
                </div>
                @error('image') <p class="mt-2 text-xs text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="description" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Character Description / Bio</label>
            <textarea name="description" id="description" rows="6" placeholder="Describe your character's personality, history, and appearance..." class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-3xl px-5 py-4 text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">{{ old('description', $character->description ?? '') }}</textarea>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-800">
            <a href="{{ route('writer.novels.characters.index', $novel) }}" class="px-8 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">Cancel</a>
            <button type="submit" class="px-10 py-4 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-600/20 transition-all">
                {{ $isEdit ? 'Update Character' : 'Create Character' }}
            </button>
        </div>
    </div>
</form>
