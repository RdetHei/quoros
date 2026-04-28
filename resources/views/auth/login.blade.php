@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-8 md:my-12 px-4 md:px-0">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="text-center mb-6 md:mb-8">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white mb-2">Masuk ke Mural</h1>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm">Akses koleksi bacaan dan lanjutkan progress kamu dengan aman.</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="text-left space-y-5 md:space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-3.5 md:py-4 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('email') border-red-500 @enderror" 
                    value="{{ old('email') }}" required placeholder="nama@email.com">
                @error('email')
                    <p class="mt-2 text-[10px] md:text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">Password</label>
                    <a href="#" class="text-[10px] md:text-xs font-bold text-indigo-600 hover:text-indigo-500">Lupa Password?</a>
                </div>
                <input type="password" name="password" id="password" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-3.5 md:py-4 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                    required placeholder="••••••••">
            </div>

            <button type="submit" class="w-full py-3.5 md:py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none text-sm md:text-base">Masuk Sekarang</button>

            <p class="text-center text-xs md:text-sm text-slate-500 dark:text-slate-400">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500">Daftar Gratis</a>
            </p>
        </form>
    </div>
</div>
@endsection
