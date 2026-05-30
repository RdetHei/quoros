@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mb-12">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-2 h-10 bg-emerald-600 rounded-full"></div>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Riwayat Bacaan</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Lanjutkan petualanganmu dari chapter terakhir yang dibaca.</p>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($histories as $history)
            <div class="group flex flex-col sm:flex-row gap-6 p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-emerald-600 hover:shadow-xl transition-all relative overflow-hidden">
                <div class="w-full sm:w-24 h-32 flex-shrink-0 rounded-2xl overflow-hidden shadow-md">
                    @if($history->novel->cover_image_url)
                        <img src="{{ $history->novel->cover_image_url }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png';">
                    @elseif($history->novel->cover_image)
                        <img src="{{ asset('storage/' . $history->novel->cover_image) }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png';">
                    @else
                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2 text-center">
                            <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $history->novel->title }}</span>
                        </div>
                    @endif
                </div>
                
                <div class="flex flex-col justify-center flex-grow">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $history->novel->title }}</h2>
                    <p class="text-sm text-slate-500 mb-4 font-medium">Penulis: {{ $history->novel->author->name }}</p>
                    
                    <div class="flex flex-wrap items-center gap-4 mt-auto">
                        <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800">
                            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-0.5 text-center">Terakhir Dibaca</p>
                            <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 text-center">{{ $history->chapter->title }}</p>
                        </div>
                        <a href="{{ route('chapters.show', [$history->novel->slug, $history->chapter->slug]) }}" class="px-6 py-2 bg-slate-900 text-white font-bold rounded-xl text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 dark:shadow-none">Lanjutkan</a>
                        <span class="text-xs text-slate-400 italic">{{ $history->updated_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
            </div>
        @empty
            <div class="py-20 text-center bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-slate-200 dark:border-slate-800">
                <p class="text-slate-500 italic">Kamu belum pernah membaca apapun di sini.</p>
                <a href="{{ route('home') }}" class="inline-block mt-4 text-emerald-600 font-bold hover:underline">Mulai Membaca Sekarang</a>
            </div>
        @endforelse
    </div>

    @if($histories->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $histories->links() }}
        </div>
    @endif
</div>
@endsection
