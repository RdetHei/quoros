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

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-2">Character Name <span class="text-neutral-400">*</span></label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $character->name ?? '') }}" placeholder="e.g. Arthur Leywin"
                        class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all">
                    @error('name') <p class="mt-2 text-xs text-neutral-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tagline" class="block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-2">Tagline</label>
                    <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $character->tagline ?? '') }}" placeholder="e.g. The returning son"
                        class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="role" class="block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-2">Story Role</label>
                        <input type="text" name="role" id="role" value="{{ old('role', $character->role ?? '') }}" placeholder="e.g. Protagonist"
                            class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all">
                    </div>
                    <div>
                        <label for="cast_type" class="block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-2">Cast</label>
                        <select name="cast_type" id="cast_type"
                            class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all">
                            @php $castType = old('cast_type', $character->cast_type ?? 'supporting'); @endphp
                            <option value="main" @selected($castType === 'main')>Main cast</option>
                            <option value="supporting" @selected($castType === 'supporting')>Supporting</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="tags" class="block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-2">Tags <span class="text-neutral-600 normal-case tracking-normal">(pisahkan dengan koma)</span></label>
                    <input type="text" name="tags" id="tags" value="{{ old('tags', $character->tags ? implode(', ', $character->tags) : '') }}" placeholder="Homecoming, Memory, Inheritance"
                        class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all">
                </div>
            </div>

            <div>
                <label class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Character Image</label>
                <div class="relative group aspect-square rounded-lg overflow-hidden bg-neutral-800 border border-dashed border-neutral-700 mt-2">
                    @if(isset($character) && $character->image_url)
                        <div id="character-placeholder" class="hidden"></div>
                        <img src="{{ $character->image_url }}" id="character-preview" class="w-full h-full object-cover" alt="">
                    @else
                        <div id="character-placeholder" class="flex flex-col items-center justify-center h-full text-neutral-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <p class="text-[10px] font-medium uppercase tracking-wider">Optional</p>
                        </div>
                        <img src="" id="character-preview" class="hidden w-full h-full object-cover" alt="">
                    @endif
                    <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/60 flex items-center justify-center">
                        <input type="file" name="photo" id="image" class="hidden" accept="image/*" onchange="initCropper(this, 'character-preview', {aspectRatio: 1, placeholderId: 'character-placeholder'})">
                        <span class="px-4 py-2 bg-white text-black rounded-lg text-xs font-medium uppercase tracking-wider">Change Image</span>
                    </label>
                </div>
                @error('photo') <p class="mt-2 text-xs text-neutral-400">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="anchor" class="block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-2">Anchor Setting</label>
                <input type="text" name="anchor" id="anchor" value="{{ old('anchor', $character->anchor ?? '') }}" placeholder="e.g. The Northern House"
                    class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all">
            </div>
            <div>
                <label for="current_chapter" class="block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-2">Current Chapter</label>
                <input type="text" name="current_chapter" id="current_chapter" value="{{ old('current_chapter', $character->current_chapter ?? '') }}" placeholder="e.g. 12 · The Language of Frost"
                    class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all">
            </div>
            <div></div>
        </div>

        <div>
            <label for="description" class="block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-2">Character Description / Bio</label>
            <textarea name="description" id="description" rows="5" placeholder="Describe your character's personality, history, and appearance..."
                class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all">{{ old('description', $character->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="wants" class="block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-2">What they want</label>
                <textarea name="wants" id="wants" rows="4" placeholder="Their driving desire..."
                    class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all">{{ old('wants', $character->wants ?? '') }}</textarea>
            </div>
            <div>
                <label for="fears" class="block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-2">What holds them back</label>
                <textarea name="fears" id="fears" rows="4" placeholder="Their fear or flaw..."
                    class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all">{{ old('fears', $character->fears ?? '') }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-neutral-800">
            <a href="{{ route('writer.novels.characters.index', $novel) }}" class="px-6 py-3 text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-white transition-colors">Cancel</a>
            <button type="submit" class="px-8 py-3 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all">
                {{ $isEdit ? 'Update Character' : 'Create Character' }}
            </button>
        </div>
    </div>
</form>
