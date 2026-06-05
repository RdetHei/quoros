@extends('layouts.writer', [
    'title' => 'Edit Character',
    'subtitle' => 'Update character details for your novel'
])

@section('content')
<div class="max-w-4xl">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 md:p-12 shadow-sm border border-slate-200 dark:border-slate-800">
        <div class="mb-10">
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Edit: {{ $character->name }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Part of <span class="font-bold text-indigo-600">{{ $novel->title }}</span></p>
        </div>

        @include('writer.novels.characters._form', ['character' => $character])
    </div>
</div>
@endsection
