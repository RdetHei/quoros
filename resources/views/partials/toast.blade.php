@php
    // Kumpulkan pesan flash dari server menjadi payload toast. `status` adalah
    // alias umum Laravel untuk keberhasilan, dipetakan ke tipe success.
    $flashToasts = collect([
        'success' => session('success') ?? session('status'),
        'error'   => session('error'),
        'warning' => session('warning'),
        'info'    => session('info'),
    ])
        ->filter(fn ($message) => filled($message))
        ->map(fn ($message, $type) => ['type' => $type, 'message' => $message])
        ->values();
@endphp

{{-- Kartu popup info global. Setiap kartu tampil 3 detik lalu menghilang. --}}
<div x-data
     x-init="$store.toast && JSON.parse($el.dataset.flashes || '[]').forEach(payload => $store.toast.push(payload))"
     data-flashes='@json($flashToasts)'
     class="pointer-events-none fixed inset-x-4 bottom-4 z-[120] flex flex-col gap-2.5 sm:bottom-5 sm:left-auto sm:right-5 sm:w-[22rem]"
     role="status"
     aria-live="polite">
    <template x-for="toast in $store.toast.items" :key="toast.id">
        <div class="pointer-events-auto relative w-full overflow-hidden rounded-xl border bg-[#121212]/98 shadow-2xl shadow-black/60 backdrop-blur"
             :class="{
                'border-[#c7a64a]/30': toast.type === 'success',
                'border-rose-500/30': toast.type === 'error',
                'border-amber-500/30': toast.type === 'warning',
                'border-sky-500/30': toast.type === 'info',
             }"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-3 sm:translate-x-5"
             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
             x-transition:leave-end="opacity-0 translate-y-2 sm:translate-x-5">
            {{-- Aksen kiri sesuai tipe --}}
            <span class="absolute inset-y-0 left-0 w-[3px]"
                  :class="{
                    'bg-[#c7a64a]': toast.type === 'success',
                    'bg-rose-500': toast.type === 'error',
                    'bg-amber-500': toast.type === 'warning',
                    'bg-sky-500': toast.type === 'info',
                  }"></span>

            <div class="flex items-start gap-3 py-3 pl-4 pr-3">
                {{-- Ikon --}}
                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full"
                      :class="{
                        'bg-[#c7a64a]/15 text-[#c7a64a]': toast.type === 'success',
                        'bg-rose-500/15 text-rose-400': toast.type === 'error',
                        'bg-amber-500/15 text-amber-400': toast.type === 'warning',
                        'bg-sky-500/15 text-sky-400': toast.type === 'info',
                      }">
                    <template x-if="toast.type === 'success'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </span>

                {{-- Teks --}}
                <div class="min-w-0 flex-1">
                    <p class="text-[13px] font-semibold leading-tight text-[#f2efe8]" x-text="toast.title"></p>
                    <p x-show="toast.message" class="mt-0.5 break-words text-xs leading-snug text-[#a3a3a3]" x-text="toast.message"></p>
                </div>

                {{-- Tombol tutup --}}
                <button type="button"
                        @click="$store.toast.dismiss(toast.id)"
                        class="-mr-0.5 -mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md text-[#6f6a62] transition-colors hover:bg-white/5 hover:text-[#f2efe8]"
                        aria-label="Tutup notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Progress bar auto-dismiss --}}
            <span class="absolute bottom-0 left-0 h-[2px]"
                  :class="{
                    'bg-[#c7a64a]': toast.type === 'success',
                    'bg-rose-500': toast.type === 'error',
                    'bg-amber-500': toast.type === 'warning',
                    'bg-sky-500': toast.type === 'info',
                  }"
                  :style="`animation: toast-progress ${$store.toast.dwell}ms linear forwards`"></span>
        </div>
    </template>
</div>
