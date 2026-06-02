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

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 space-y-5">
        <div>
            <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nama <span class="text-rose-500">*</span></label>
            <input type="text" name="name" id="name" required value="{{ old('name', $character->name ?? '') }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
            @error('name') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="role" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Peran</label>
            <input type="text" name="role" id="role" value="{{ old('role', $character->role ?? '') }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
        </div>

        <div>
            <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi</label>
            <textarea name="description" id="description" rows="5" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">{{ old('description', $character->description ?? '') }}</textarea>
        </div>

        <div>
            <label for="photo" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Foto</label>
            <input type="file" name="photo" id="photo" accept="image/*" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm">
            @error('photo') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror

            @if (! empty($character?->image_url))
                <img src="{{ $character->image_url }}" alt="{{ $character->name }}" class="mt-4 h-28 w-28 object-cover rounded-xl border border-slate-200 dark:border-slate-700">
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between">
            <a href="{{ route('writer.novels.characters.index', $novel) }}" class="px-6 py-3 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Kembali</a>
            <button type="submit" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Karakter' }}
            </button>
        </div>
    </div>
</form>
