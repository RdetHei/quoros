@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-white/10 bg-[#070b12] shadow-[0_20px_40px_rgba(0,0,0,0.35)] overflow-hidden">
        <div class="border-b border-white/10 bg-white/[0.02] px-5 py-4 sm:px-6">
            <div class="flex items-center justify-between gap-3">
                <span class="inline-flex items-center rounded-full border border-indigo-400/30 bg-indigo-500/10 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-indigo-200">
                    {{ $notification->type->value ?? 'announcement' }}
                </span>
                <span class="text-[10px] uppercase tracking-[0.14em] text-slate-500">{{ $notification->created_at->translatedFormat('d M Y') }}</span>
            </div>
        </div>

        <div class="px-5 py-6 sm:px-6 sm:py-8">
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">{{ $notification->title() }}</h1>

            <div class="mt-5 rounded-2xl border border-white/10 bg-black/30 p-4 sm:p-5">
                <p class="text-sm leading-7 text-slate-200 whitespace-pre-line">{{ $notification->body() }}</p>
            </div>

            @if($notification->url())
                <div class="mt-6 rounded-2xl border border-indigo-400/20 bg-indigo-500/5 p-4">
                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-indigo-200">Tautan terkait</p>
                    <a href="{{ $notification->url() }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex break-all text-sm font-medium text-indigo-300 hover:text-indigo-200">
                        {{ $notification->url() }}
                    </a>
                </div>
            @endif

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('notifications.index') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/[0.03] px-4 py-2.5 text-sm font-semibold text-slate-200 hover:border-white/20 hover:bg-white/[0.05]">
                    Kembali ke notifikasi
                </a>
                @if($notification->url())
                    <a href="{{ $notification->url() }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-xl bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-400">
                        Buka link
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection