@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 rounded-full text-emerald-500 text-[10px] font-black uppercase tracking-widest mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" /></svg>
                Management
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Novel Saya</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Kelola semua karya tulis yang telah kamu publikasikan dan monitor performanya.</p>
        </div>
        <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-slate-900 text-white font-black rounded-[1.25rem] hover:bg-slate-800 transition-all shadow-xl shadow-slate-500/10 active:scale-95 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
            Buat Novel Baru
        </a>
    </div>

    <!-- Content Card -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30">
                        <th class="pl-8 pr-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Novel & Informasi</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Genre Utama</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Total Bab</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Insight Performa</th>
                        <th class="pl-6 pr-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Aksi Kelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($novels as $novel)
                        <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-all duration-300">
                            <td class="pl-8 pr-6 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="relative w-16 h-22 flex-shrink-0 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 shadow-md group-hover:shadow-lg transition-shadow duration-300">
                                        @if($novel->cover_image_url)
                                            <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                        @elseif($novel->cover_image)
                                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center p-2 text-[8px] font-black text-slate-400 text-center uppercase">No Cover</div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-black text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors mb-1 truncate text-lg">{{ $novel->title }}</h4>
                                        <div class="flex items-center gap-3">
                                            <span class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                {{ $novel->created_at->format('d M Y') }}
                                            </span>
                                            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest {{ $novel->status === 'ongoing' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-slate-500/10 text-slate-600 border border-slate-500/20' }}">
                                                {{ $novel->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($novel->genres->take(2) as $genre)
                                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-black rounded-lg uppercase tracking-wider">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-xl font-black text-slate-900 dark:text-white">{{ $novel->chapters_count }}</span>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Chapters</span>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex items-center justify-center gap-6">
                                    <div class="flex flex-col items-center group/stat">
                                        <span class="text-sm font-black text-slate-900 dark:text-white group-hover/stat:text-emerald-600 transition-colors">{{ number_format($novel->view_count) }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Views</span>
                                    </div>
                                    <div class="flex flex-col items-center group/stat">
                                        <span class="text-sm font-black text-slate-900 dark:text-white group-hover/stat:text-rose-600 transition-colors">{{ $novel->bookmarks_count }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Saved</span>
                                    </div>
                                    <div class="flex flex-col items-center group/stat">
                                        <span class="text-sm font-black text-amber-500">{{ number_format($novel->rating_avg, 1) }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Rating</span>
                                    </div>
                                </div>
                            </td>
                            <td class="pl-6 pr-8 py-6 text-right">
                                <div class="flex justify-end items-center gap-1.5">
                                    <a href="{{ route('novels.show', $novel->slug) }}" class="p-2.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl transition-all" title="View Novel">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    @can('update', $novel)
                                    <a href="{{ route('writer.novels.edit', $novel->id) }}" class="p-2.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-all" title="Edit Novel">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    @endcan
                                    @can('delete', $novel)
                                    <form action="{{ route('writer.novels.destroy', $novel->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all" onclick="return confirm('Hapus novel ini?')" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6 text-slate-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </div>
                                <h4 class="text-xl font-black text-slate-900 dark:text-white mb-2">Belum Ada Karya</h4>
                                <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-8 font-medium">Kamu belum membuat novel apapun. Mulai menulis sekarang dan bagikan ceritamu!</p>
                                <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all">
                                    Mulai Menulis
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
