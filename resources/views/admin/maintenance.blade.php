@extends('layouts.admin')

@php
    $adminTitle = 'Maintenance Mode';
    $adminBreadcrumbs = ['Admin', 'System', 'Maintenance Mode'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-3xl space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="admin-kicker">System / availability</p>
            <h2 class="admin-display mt-1">Maintenance Mode</h2>
            <p class="mt-2 text-sm text-slate-400">Saat aktif, pengunjung biasa akan melihat halaman pemeliharaan. Administrator tetap dapat mengakses dashboard.</p>
        </div>
        @if($status['active'])
            <span class="console-badge warn">● Maintenance aktif</span>
        @else
            <span class="console-badge">● Situs online</span>
        @endif
    </div>

    <section class="console-card p-6">
        <p class="admin-kicker">Status saat ini</p>
        @if($status['active'])
            <p class="mt-2 font-serif text-lg text-amber-300">Situs sedang dalam mode pemeliharaan.</p>
            <dl class="mt-4 divide-y divide-white/5 border-t border-white/5 text-sm">
                <div class="flex items-center justify-between gap-3 py-2.5">
                    <dt class="admin-kicker">Diaktifkan sejak</dt>
                    <dd class="font-mono text-xs text-zinc-300">{{ $status['since'] ? $status['since']->format('d M Y · H:i') : '—' }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3 py-2.5">
                    <dt class="admin-kicker">Bypass secret</dt>
                    <dd class="font-mono text-xs text-zinc-300">{{ $status['secret'] ?: '—' }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3 py-2.5">
                    <dt class="admin-kicker">Retry-After</dt>
                    <dd class="font-mono text-xs text-zinc-300">{{ $status['retry'] ? $status['retry'].' detik' : '—' }}</dd>
                </div>
                <div class="flex items-start justify-between gap-3 py-2.5">
                    <dt class="admin-kicker">Pesan publik</dt>
                    <dd class="max-w-[60%] text-right font-serif text-xs text-zinc-300">{{ $status['message'] ?: 'Pesan bawaan' }}</dd>
                </div>
            </dl>

            <form action="{{ route('admin.maintenance.toggle') }}" method="POST" class="mt-5 flex justify-end">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" value="disable">
                <button type="submit" class="console-btn">Kembalikan situs online</button>
            </form>
        @else
            <p class="mt-2 font-serif text-lg text-emerald-300">Situs berjalan normal untuk semua pengguna.</p>
        @endif
    </section>

    @unless($status['active'])
        <section class="console-card p-6">
            <p class="admin-kicker">Aktifkan pemeliharaan</p>
            <form action="{{ route('admin.maintenance.toggle') }}" method="POST" class="mt-4 space-y-5">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" value="enable">

                <div>
                    <label class="console-label" for="message">Pesan pemeliharaan (opsional)</label>
                    <textarea name="message" id="message" rows="3" maxlength="255" placeholder="Kami sedang melakukan pemeliharaan terjadwal..." class="console-textarea @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="console-label" for="secret">Bypass secret (opsional)</label>
                        <input type="text" name="secret" id="secret" value="{{ old('secret') }}" placeholder="mis. deploy-2026" class="console-input @error('secret') is-invalid @enderror">
                        <p class="mt-2 text-xs text-zinc-500">Kunjungi <span class="font-mono">/secret</span> untuk melewati mode ini.</p>
                        @error('secret') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="console-label" for="retry">Retry-After (detik)</label>
                        <input type="number" name="retry" id="retry" value="{{ old('retry') }}" min="0" max="3600" placeholder="60" class="console-input @error('retry') is-invalid @enderror">
                        @error('retry') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="submit" class="console-btn danger">Aktifkan pemeliharaan</button>
                </div>
            </form>
        </section>
    @endunless
</div>
@endsection
