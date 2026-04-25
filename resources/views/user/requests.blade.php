@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mb-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div class="flex items-center gap-4">
            <div class="w-2 h-10 bg-violet-600 rounded-full"></div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Request Novel</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium">Punya judul favorit yang belum ada? Beritahu kami!</p>
            </div>
        </div>
        
        @auth
            <button onclick="document.getElementById('request-form').scrollIntoView({behavior: 'smooth'})" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 transition-all">Buat Permintaan</button>
        @endauth
    </div>

    <div class="grid grid-cols-1 gap-6 mb-16">
        @forelse($requests as $request)
            <div class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex-grow">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-wide leading-none">{{ $request->title }}</h3>
                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full 
                            {{ $request->status === 'fulfilled' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800' : '' }}
                            {{ $request->status === 'pending' ? 'bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800' : '' }}
                            {{ $request->status === 'rejected' ? 'bg-rose-50 text-rose-600 border border-rose-100 dark:bg-rose-900/20 dark:text-rose-400 dark:border-rose-800' : '' }}
                        ">
                            {{ $request->status === 'fulfilled' ? 'Accepted' : ($request->status === 'rejected' ? 'Declined' : 'Pending') }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 mb-3 italic line-clamp-2">"{{ $request->description ?: 'Tidak ada deskripsi.' }}"</p>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-bold text-indigo-500">
                            {{ substr($request->user->name, 0, 1) }}
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Diminta oleh {{ $request->user->name }} • {{ $request->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-20 text-center bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-dashed border-slate-200 dark:border-slate-800">
                <p class="text-slate-500 italic">Belum ada permintaan novel.</p>
            </div>
        @endforelse
        
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>

    @auth
        <div id="request-form" class="bg-indigo-600 rounded-[3rem] p-8 md:p-16 text-white shadow-2xl shadow-indigo-200 dark:shadow-none relative overflow-hidden">
            <div class="absolute top-0 right-0 p-12 opacity-10 rotate-12 transform translate-x-1/4 -translate-y-1/4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-64 w-64" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </div>
            
            <div class="relative z-10 max-w-xl">
                <h2 class="text-3xl font-black mb-4">Ingin Baca Sesuatu?</h2>
                <p class="text-indigo-100 mb-10 font-medium">Tuliskan judul novel atau penulis yang kamu inginkan, tim kami akan berusaha mencarinya!</p>
                
                <form action="{{ route('requests.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="title" class="block text-xs font-bold text-indigo-200 uppercase tracking-widest mb-3">Judul Novel / Penulis</label>
                        <input type="text" name="title" id="title" required
                            class="w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-6 py-4 text-sm text-white placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all" 
                            placeholder="Contoh: Lord of the Mysteries">
                    </div>
                    <div>
                        <label for="description" class="block text-xs font-bold text-indigo-200 uppercase tracking-widest mb-3">Catatan Tambahan (Opsional)</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-6 py-4 text-sm text-white placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all" 
                            placeholder="Kenapa kamu merekomendasikan novel ini?"></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 bg-white text-indigo-600 font-bold rounded-2xl shadow-xl hover:bg-indigo-50 transition-all transform hover:-translate-y-1">Kirim Permintaan</button>
                </form>
            </div>
        </div>
    @else
        <div class="bg-slate-900 rounded-[3rem] p-12 text-center text-white">
            <h2 class="text-2xl font-bold mb-4">Ingin Request Novel?</h2>
            <p class="text-slate-400 mb-8">Kamu harus masuk ke akun kamu terlebih dahulu untuk membuat permintaan.</p>
            <a href="{{ route('login') }}" class="inline-block px-10 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all">Login Sekarang</a>
        </div>
    @endauth
</div>
@endsection
