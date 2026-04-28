@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-8 md:my-12 px-4 md:px-0">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="text-center mb-6 md:mb-8">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white mb-2">Buat Akun Mural</h1>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm">Daftar sekali untuk mulai menyimpan riwayat baca dan bookmark kamu.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="text-left space-y-5 md:space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-3.5 md:py-4 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('name') border-red-500 @enderror" 
                    value="{{ old('name') }}" required placeholder="Nama lengkap kamu">
                @error('name')
                    <p class="mt-2 text-[10px] md:text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

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
                <label for="password" class="block text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Password</label>
                <input type="password" name="password" id="password" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-3.5 md:py-4 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('password') border-red-500 @enderror" 
                    required placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="mt-2 text-[10px] md:text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-3.5 md:py-4 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                    required placeholder="Ulangi password">
            </div>

            <p class="text-[10px] md:text-xs text-slate-500 dark:text-slate-400">Dengan mendaftar, akun akan otomatis dibuat sebagai pembaca.</p>

            <button type="submit" class="w-full py-3.5 md:py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none text-sm md:text-base">Daftar Sekarang</button>

            <p class="text-center text-xs md:text-sm text-slate-500 dark:text-slate-400">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500">Masuk Sini</a>
            </p>
        </form>
    </div>
</div>
@endsection
