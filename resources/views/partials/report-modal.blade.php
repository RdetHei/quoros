@php
    use App\Enums\ReportReason;
@endphp

<div x-data="{
        open: false,
        type: '',
        id: '',
        label: '',
        openReport(detail) {
            this.type = detail.type;
            this.id = detail.id;
            this.label = detail.label || '';
            this.open = true;
        }
    }"
    x-on:open-report.window="openReport($event.detail)"
    x-cloak>
    <div x-show="open"
         class="fixed inset-0 z-[110] flex items-center justify-center p-4"
         style="display: none;">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden"
             @click.stop>
            <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Laporkan konten</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1" x-show="label" x-text="label"></p>
            </div>
            <form action="{{ route('reports.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="reportable_type" x-model="type" required>
                <input type="hidden" name="reportable_id" x-model="id" required>

                <div>
                    <label for="report-reason" class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Alasan</label>
                    <select name="reason" id="report-reason" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-900 dark:text-white px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @foreach(ReportReason::cases() as $reason)
                            <option value="{{ $reason->value }}">{{ $reason->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="report-details" class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Detail (wajib jika &quot;Lainnya&quot;)</label>
                    <textarea name="details" id="report-details" rows="4" maxlength="2000"
                              placeholder="Jelaskan masalahnya..."
                              class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-900 dark:text-white px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="open = false"
                            class="flex-1 px-4 py-2.5 text-sm font-bold rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 text-sm font-bold rounded-xl bg-rose-600 hover:bg-rose-700 text-white transition-colors">
                        Kirim laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
