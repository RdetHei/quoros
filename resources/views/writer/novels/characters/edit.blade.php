@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto my-8 space-y-6">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Edit Karakter</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Novel: {{ $novel->title }}</p>
    </div>

    @include('writer.novels.characters._form', ['character' => $character])
</div>
@endsection
