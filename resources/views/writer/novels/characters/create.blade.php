@extends('layouts.writer', [
    'title' => 'Add New Character',
    'subtitle' => 'Define a new character for your story'
])

@section('content')
<div class="max-w-4xl">
    <div class="bg-neutral-900 rounded-xl p-8 md:p-10 border border-neutral-800">
        <div class="mb-8">
            <h1 class="text-xl font-semibold text-white tracking-tight">Create Character</h1>
            <p class="text-sm text-neutral-400 mt-2">Adding character to <span class="font-medium text-white">{{ $novel->title }}</span></p>
        </div>

        @include('writer.novels.characters._form')
    </div>
</div>
@endsection
