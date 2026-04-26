@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Novel Saya</h1>
        <p class="text-slate-500 dark:text-slate-400">Kelola semua karya tulis yang telah kamu publikasikan.</p>
    </div>
    <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
        Buat Novel Baru
    </a>
</div>

<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50">
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Novel</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Genre</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Chapter</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Stats</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($novels as $novel)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-16 flex-shrink-0 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800">
                                    @if($novel->cover_image)
                                        <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center p-1 text-[8px] font-bold text-slate-400 text-center">NO COVER</div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white line-clamp-1">{{ $novel->title }}</h4>
                                    <p class="text-xs text-slate-500">{{ $novel->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($novel->genres->take(2) as $genre)
                                    <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold rounded-full border border-indigo-100 dark:border-indigo-800">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-400">
                                {{ $novel->chapters_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-4">
                                <div class="flex flex-col items-center">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white">{{ number_format($novel->view_count) }}</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-tighter">Views</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $novel->bookmarks_count }}</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-tighter">Saved</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-xs font-bold text-amber-500">{{ number_format($novel->rating_avg, 1) }}</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-tighter">Rating</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('novels.show', $novel->slug) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                </a>
                                <a href="{{ route('writer.novels.edit', $novel->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                </a>
                                <form action="{{ route('writer.novels.destroy', $novel->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors" onclick="return confirm('Hapus novel ini?')" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500 italic">
                            Kamu belum membuat novel apapun. Mulai menulis sekarang!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
