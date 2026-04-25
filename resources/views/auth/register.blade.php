@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 text-center">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2">Buat Akun</h1>
        <p class="text-slate-500 dark:text-slate-400 mb-8">Bergabung dengan ribuan pembaca lainnya di Mural.</p>

        <form action="{{ route('register') }}" method="POST" class="text-left">
            @csrf
            <div class="mb-6">
                <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('name') border-red-500 @enderror" 
                    value="{{ old('name') }}" required placeholder="Nama lengkap kamu">
                @error('name')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('email') border-red-500 @enderror" 
                    value="{{ old('email') }}" required placeholder="nama@email.com">
                @error('email')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Password</label>
                <input type="password" name="password" id="password" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('password') border-red-500 @enderror" 
                    required placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                    required placeholder="Ulangi password">
            </div>

            <div class="mb-8">
                <label for="role" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Daftar Sebagai</label>
                <select name="role" id="role" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                    <option value="user">Pembaca (User)</option>
                    <option value="writer">Penulis (Writer)</option>
                </select>
            </div>

            <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none mb-6">Daftar Sekarang</button>

            <p class="text-center text-sm text-slate-500 dark:text-slate-400">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500">Masuk Sini</a>
            </p>
        </form>
    </div>
</div>
@endsection
