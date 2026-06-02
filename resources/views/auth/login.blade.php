@extends('layouts.auth')

@section('title', 'Login')

@section('eyebrow', 'Reader account')
@section('heading', 'Login to Quoros')
@section('subheading', 'Continue reading, manage bookmarks, and sync history across all devices.')

@section('content')
<form action="{{ route('login') }}" method="POST" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-xs font-semibold text-slate-300 mb-2">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
               placeholder="nama@email.com"
               class="auth-input @error('email') auth-input-error @enderror">
        @error('email')
            <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div x-data="{ show: false }">
        <div class="flex justify-between items-center mb-2">
            <label for="password" class="block text-xs font-semibold text-slate-300">Password</label>
            <a href="#" class="text-xs font-semibold text-amber-500/90 hover:text-amber-400 transition-colors">Forgot password?</a>
        </div>
        <div class="relative">
            <input :type="show ? 'text' : 'password'" name="password" id="password" required autocomplete="current-password"
                   placeholder="••••••••"
                   class="auth-input pr-12">
            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-slate-500 hover:text-slate-300 transition-colors" aria-label="Show password">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                </svg>
            </button>
        </div>
    </div>

    <button type="submit" class="auth-btn-primary w-full">
        Login
    </button>

    <p class="text-center text-sm text-slate-400 pt-2">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-semibold text-amber-500 hover:text-amber-400 transition-colors">Register for free</a>
    </p>
</form>
@endsection
