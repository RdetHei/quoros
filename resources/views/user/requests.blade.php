@extends('layouts.app')

@section('content')
<div class="mx-auto mb-12 max-w-5xl pb-14 pt-8">
    <div class="mb-8 flex flex-col gap-6 rounded-[28px] border border-neutral-800 bg-[#10161d] p-6 shadow-[0_22px_60px_rgba(0,0,0,0.28)] md:flex-row md:items-end md:justify-between md:p-7">
        <div class="flex items-center gap-4">
            <div class="h-10 w-1 rounded-full bg-neutral-400"></div>
            <div>
                <p class="mb-2 text-[10px] font-medium uppercase tracking-[0.24em] text-neutral-500">Community</p>
                <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">Novel Request</h1>
            </div>
        </div>

        @auth
            <button onclick="document.getElementById('request-form').scrollIntoView({behavior: 'smooth'})" class="inline-flex items-center justify-center rounded-xl border border-neutral-700 bg-white px-6 py-3 text-sm font-semibold text-neutral-900 transition-colors hover:bg-neutral-200">
                Create Request
            </button>
        @endauth
    </div>

    <div class="mb-16 grid grid-cols-1 gap-5">
        @forelse($requests as $request)
            <div class="flex flex-col items-start justify-between gap-5 rounded-[24px] border border-neutral-800 bg-[#0d1218] p-6 sm:flex-row sm:items-center">
                <div class="min-w-0 flex-1">
                    <div class="mb-2 flex flex-wrap items-center gap-3">
                        <h3 class="text-lg font-semibold uppercase tracking-wide text-white">{{ $request->title }}</h3>
                        <span class="rounded-full border border-neutral-700 bg-neutral-900 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-neutral-300
                            {{ $request->status === 'fulfilled' ? 'border-emerald-700/40 bg-emerald-500/10 text-emerald-300' : '' }}
                            {{ $request->status === 'pending' ? 'border-amber-700/40 bg-amber-500/10 text-amber-300' : '' }}
                            {{ $request->status === 'rejected' ? 'border-neutral-700 bg-neutral-800 text-neutral-400' : '' }}
                        ">
                            {{ $request->status === 'fulfilled' ? 'Accepted' : ($request->status === 'rejected' ? 'Declined' : 'Pending') }}
                        </span>
                    </div>
                    <p class="mb-3 text-sm italic text-neutral-400">"{{ $request->description ?: 'No description.' }}"</p>
                    <div class="flex items-center gap-2 text-[10px] font-medium uppercase tracking-[0.18em] text-neutral-500">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-neutral-800 text-neutral-300">
                            {{ substr($request->user->name, 0, 1) }}
                        </div>
                        <span>Requested by {{ $request->user->name }} • {{ $request->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-[24px] border border-dashed border-neutral-700 bg-[#0d1218] px-6 py-20 text-center">
                <p class="text-neutral-400">No novel requests yet.</p>
            </div>
        @endforelse

        @if($requests->hasPages())
            <div class="mt-2 flex justify-center">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    @auth
        <div id="request-form" class="relative overflow-hidden rounded-[30px] border border-neutral-800 bg-[#111821] p-8 text-white md:p-12">
            <div class="absolute right-0 top-0 p-8 opacity-10 md:p-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-40 w-40 md:h-64 md:w-64" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </div>

            <div class="relative z-10 max-w-xl">
                <h2 class="mb-4 text-3xl font-bold tracking-tight">Want to read something?</h2>
                <p class="mb-10 text-neutral-400">Tell us the title or author you want, and we’ll keep an eye out for it.</p>

                <form action="{{ route('requests.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="title" class="mb-3 block text-[10px] font-medium uppercase tracking-[0.22em] text-neutral-400">Novel Title / Author</label>
                        <input type="text" name="title" id="title" required class="w-full rounded-2xl border border-neutral-700 bg-[#0d1218] px-5 py-4 text-sm text-white placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none" placeholder="Example: Lord of the Mysteries">
                    </div>
                    <div>
                        <label for="description" class="mb-3 block text-[10px] font-medium uppercase tracking-[0.22em] text-neutral-400">Additional Notes (Optional)</label>
                        <textarea name="description" id="description" rows="4" class="w-full rounded-2xl border border-neutral-700 bg-[#0d1218] px-5 py-4 text-sm text-white placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none" placeholder="Why do you recommend this novel?"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-2xl bg-white px-5 py-4 text-sm font-semibold text-neutral-900 transition-colors hover:bg-neutral-200">Submit Request</button>
                </form>
            </div>
        </div>
    @else
        <div class="rounded-[30px] border border-neutral-800 bg-[#111821] p-12 text-center text-white">
            <h2 class="mb-4 text-2xl font-bold">Want to request a novel?</h2>
            <p class="mb-8 text-neutral-400">You need to log in before submitting a request.</p>
            <a href="{{ route('login') }}" class="inline-block rounded-2xl bg-white px-8 py-4 text-sm font-semibold text-neutral-900 transition-colors hover:bg-neutral-200">Login Now</a>
        </div>
    @endauth
</div>
@endsection
