@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto my-8 space-y-6">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Create New Novel</h1>
        <p class="text-slate-500 dark:text-slate-400">Gunakan wizard 3 langkah untuk proses pembuatan novel.</p>
    </div>

    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl px-5 py-4">
        <p class="text-sm text-amber-700 dark:text-amber-300">
            Form legacy ini tidak lagi dipakai untuk menambah karakter.
            Pembuatan karakter dilakukan dari halaman khusus setelah novel selesai dibuat.
        </p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('writer.novels.create.step-1') }}" class="inline-flex items-center justify-center px-8 py-4 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all">
                Mulai Wizard Novel
            </a>
            <a href="{{ route('writer.novels.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                Kembali ke Daftar Novel
            </a>
        </div>
    </div>
</div>
@endsection
