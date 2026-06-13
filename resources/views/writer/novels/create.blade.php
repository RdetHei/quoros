@extends('layouts.writer', [
    'title' => 'Create New Novel',
    'subtitle' => 'Launch your next masterpiece using our 3-step creation wizard.'
])

@section('content')
<div class="space-y-8">
    <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-3xl px-6 py-5 flex items-start gap-4">
        <div class="p-2 bg-indigo-600 rounded-xl text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-indigo-700 dark:text-indigo-300">New Process Information</p>
            <p class="text-xs text-indigo-600/80 dark:text-indigo-400/80 mt-1 leading-relaxed">
                We've updated our creation process. Characters and specific chapter settings can now be managed from the Novel Dashboard after you finish this initial setup.
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-sm text-center">
        <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-900/30 rounded-3xl flex items-center justify-center mx-auto mb-8 text-indigo-600 dark:text-indigo-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4">Ready to start?</h3>
        <p class="text-slate-500 dark:text-slate-400 mb-10 max-w-md mx-auto leading-relaxed text-sm font-medium">
            Our wizard will guide you through setting up your novel's identity, synopsis, and categories.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('writer.novels.create.step-1') }}" class="w-full sm:w-auto px-10 py-4 bg-indigo-600 text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">
                Launch Wizard
            </a>
            <a href="{{ route('dashboard', ['tab' => 'library']) }}" class="w-full sm:w-auto px-10 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                Cancel
            </a>
        </div>
    </div>
</div>
@endsection
