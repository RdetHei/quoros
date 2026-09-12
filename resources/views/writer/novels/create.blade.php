@extends('layouts.writer', [
    'title' => 'Create New Novel',
    'subtitle' => 'Launch your next masterpiece using our 3-step creation wizard.'
])

@section('content')
<div class="space-y-6">
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl px-6 py-5 flex items-start gap-4">
        <div class="p-2 bg-white rounded-lg text-black shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-white">New Process Information</p>
            <p class="text-xs text-neutral-400 mt-1 leading-relaxed">
                Characters and specific chapter settings can be managed from the Novel Workspace after you finish this initial setup.
            </p>
        </div>
    </div>

    <div class="bg-neutral-900 rounded-xl p-10 border border-neutral-800 text-center">
        <div class="w-16 h-16 bg-neutral-800 border border-neutral-700 rounded-xl flex items-center justify-center mx-auto mb-6 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-3">Ready to start?</h3>
        <p class="text-neutral-400 mb-8 max-w-md mx-auto leading-relaxed text-sm">
            Our wizard will guide you through setting up your novel's identity, synopsis, and categories.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('writer.novels.create.step-1') }}" class="w-full sm:w-auto px-8 py-3 bg-white text-black font-medium uppercase tracking-wider text-xs rounded-lg hover:bg-neutral-200 transition-all">
                Launch Wizard
            </a>
            <a href="{{ route('dashboard', ['tab' => 'library']) }}" class="w-full sm:w-auto px-8 py-3 bg-neutral-800 text-neutral-300 font-medium uppercase tracking-wider text-xs rounded-lg hover:bg-neutral-700 hover:text-white transition-all border border-neutral-700">
                Cancel
            </a>
        </div>
    </div>
</div>
@endsection
