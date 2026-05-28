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
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-3.5 md:py-4 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white dark:focus:bg-slate-900 transition-all @error('email') border-red-500 @enderror" 
                    value="{{ old('email') }}" required placeholder="nama@email.com">
                @error('email')
                    <p class="mt-2 text-[10px] md:text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ show: false }">
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">Password</label>
                    <a href="#" class="text-[10px] md:text-xs font-bold text-emerald-600 hover:text-emerald-500">Lupa Password?</a>
                </div>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" id="password" 
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-3.5 md:py-4 pr-12 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                        required placeholder="••••••••">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.882 9.882L9.75 9.75M13.125 13.125l.125.125m-4.75 4.75l-4.75-4.75M21 21l-4.75-4.75M21 3l-4.75 4.75" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 md:py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 dark:shadow-none text-sm md:text-base">Masuk Sekarang</button>

            <p class="text-center text-xs md:text-sm text-slate-500 dark:text-slate-400">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-500">Daftar Gratis</a>
            </p>
        </form>
    </div>
</div>
@endsection
