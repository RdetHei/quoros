@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 text-center">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2">Selamat Datang</h1>
        <p class="text-slate-500 dark:text-slate-400 mb-8">Masuk ke akun Mural kamu untuk melanjutkan membaca.</p>

        <form action="{{ route('login') }}" method="POST" class="text-left">
            @csrf
            <div class="mb-6">
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('email') border-red-500 @enderror" 
                    value="{{ old('email') }}" required placeholder="nama@email.com">
                @error('email')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Password</label>
                    <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-500">Lupa Password?</a>
                </div>
                <input type="password" name="password" id="password" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                    required placeholder="••••••••">
            </div>

            <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none mb-6">Masuk Sekarang</button>

            <p class="text-center text-sm text-slate-500 dark:text-slate-400">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500">Daftar Gratis</a>
            </p>
        </form>
    </div>
</div>
@endsection
