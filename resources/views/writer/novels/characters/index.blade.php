@extends('layouts.writer', [
    'title' => 'Character Management',
    'subtitle' => 'Novel: ' . $novel->title
])

@section('content')
<div class="space-y-6">
    <div class="bg-neutral-900 rounded-xl p-6 border border-neutral-800">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-white tracking-tight">Manajemen Karakter</h1>
                <p class="mt-1 text-sm text-neutral-400">{{ $novel->title }}</p>
            </div>
            <a href="{{ route('writer.novels.characters.create', $novel) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-black text-sm font-medium rounded-lg hover:bg-neutral-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Tambah Karakter
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 bg-neutral-900 border border-neutral-700 rounded-lg text-sm text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-neutral-900 rounded-xl p-6 border border-neutral-800">
        @if($novel->characters->isEmpty())
            <p class="text-sm text-neutral-500">Belum ada karakter. Tambahkan karakter pertama untuk novel ini.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($novel->characters as $character)
                    <div class="rounded-xl border border-neutral-800 bg-black/40 overflow-hidden hover:border-neutral-600 transition-colors">
                        <div class="h-44 bg-neutral-800">
                            @if($character->image_url)
                                <img src="{{ $character->image_url }}" alt="{{ $character->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-neutral-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 space-y-2">
                            <h3 class="font-medium text-white">{{ $character->name }}</h3>
                            <p class="text-xs text-neutral-500">{{ $character->role ?: 'Tanpa peran' }}</p>
                            <p class="text-sm text-neutral-400 line-clamp-3">{{ $character->description ?: 'Tanpa deskripsi.' }}</p>
                            <div class="pt-2 flex items-center gap-2">
                                <a href="{{ route('writer.novels.characters.edit', [$novel, $character]) }}" class="px-3 py-1.5 text-xs font-medium text-black bg-white rounded-md hover:bg-neutral-200 transition-colors">Edit</a>
                                <form action="{{ route('writer.novels.characters.destroy', [$novel, $character]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-neutral-400 border border-neutral-700 rounded-md hover:text-white hover:border-neutral-500 transition-colors" onclick="return confirm('Hapus karakter ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
