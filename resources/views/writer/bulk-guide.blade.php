@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">
                Guide Format <span class="text-indigo-600">Bulk Upload</span>
            </h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
                Pelajari cara mengatur dokumen Anda agar sistem Quoros dapat mendeteksi bab secara otomatis dengan sempurna.
            </p>
        </div>

        <!-- Format Selection Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">EPUB</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Paling direkomendasikan. Struktur metadata bawaan menjamin urutan bab yang akurat.</p>
            </div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">DOCX</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Gunakan format Heading atau judul teks tebal untuk memisahkan antar bab.</p>
            </div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                <div class="w-12 h-12 bg-rose-50 dark:bg-rose-900/30 rounded-2xl flex items-center justify-center text-rose-600 dark:text-rose-400 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">PDF</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Gunakan baris baru yang diawali kata kunci seperti "Chapter" untuk pemisahan.</p>
            </div>
        </div>

        <!-- Detailed Guide Section -->
        <div class="space-y-8">
            <!-- Rule 1: Keywords -->
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-10 border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/5 rounded-full -mr-16 -mt-16"></div>
                
                <div class="flex items-start gap-6 mb-8">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black flex-shrink-0">1</div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Gunakan Kata Kunci Pemisah</h2>
                        <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Sistem kami mengenali beberapa kata kunci populer di awal baris untuk mendeteksi awal bab baru.</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach(['Chapter', 'Bab', 'Episode', 'Part', 'Eps', 'Chp', 'Bagian'] as $kw)
                        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl text-center border border-slate-100 dark:border-slate-700">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $kw }}</span>
                        </div>
                    @endforeach
                    <div class="px-4 py-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl text-center border border-indigo-100 dark:border-indigo-800/50">
                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">Dan Angka</span>
                    </div>
                </div>
            </div>

            <!-- Rule 2: Heading Styles -->
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-10 border border-slate-100 dark:border-slate-800 shadow-sm">
                <div class="flex items-start gap-6 mb-8">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black flex-shrink-0">2</div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Gunakan Gaya Judul (Heading)</h2>
                        <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Untuk DOCX dan EPUB, sangat disarankan menggunakan style <code class="bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded text-indigo-600">Heading 1</code> atau <code class="bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded text-indigo-600">Heading 2</code>.</p>
                    </div>
                </div>

                <div class="p-6 bg-slate-950 rounded-3xl font-mono text-sm overflow-x-auto border border-slate-800">
                    <div class="text-emerald-400 mb-2">// Contoh Struktur DOCX / Word</div>
                    <div class="text-slate-300 font-bold text-lg mb-1">Bab 1: Awal Perjalanan</div>
                    <div class="text-slate-500 mb-4">Isi cerita bab satu dimulai di sini...</div>
                    <div class="text-slate-300 font-bold text-lg mb-1">Bab 2: Pertemuan Misterius</div>
                    <div class="text-slate-500">Isi cerita bab dua berlanjut di sini...</div>
                </div>
            </div>

            <!-- Best Practices -->
            <div class="bg-indigo-600 rounded-[3rem] p-10 text-white shadow-xl shadow-indigo-200 dark:shadow-none">
                <h3 class="text-2xl font-black mb-6 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    Tips Terbaik
                </h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <p class="text-indigo-50 leading-relaxed">Pastikan judul bab berada di baris baru yang terpisah dari paragraf isi.</p>
                    </li>
                    <li class="flex items-start gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <p class="text-indigo-50 leading-relaxed">Hindari penggunaan tabel di dalam dokumen jika memungkinkan.</p>
                    </li>
                    <li class="flex items-start gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <p class="text-indigo-50 leading-relaxed">Sistem akan otomatis membersihkan spasi berlebih dan format yang tidak kompatibel.</p>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Footer Action -->
        <div class="mt-16 text-center">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-bold hover:gap-4 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Panel Penulis
            </a>
        </div>
    </div>
</div>
@endsection
