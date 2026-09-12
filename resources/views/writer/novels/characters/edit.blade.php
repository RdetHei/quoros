@extends('layouts.writer', [
    'title' => 'Edit Character',
    'subtitle' => 'Update character details for your novel'
])

@section('content')
<div class="max-w-4xl">
    <div class="bg-neutral-900 rounded-xl p-8 md:p-10 border border-neutral-800">
        <div class="mb-8">
            <h1 class="text-xl font-semibold text-white tracking-tight">Edit: {{ $character->name }}</h1>
            <p class="text-sm text-neutral-400 mt-2">Part of <span class="font-medium text-white">{{ $novel->title }}</span></p>
        </div>

        @include('writer.novels.characters._form', ['character' => $character])
    </div>
</div>
@endsection
