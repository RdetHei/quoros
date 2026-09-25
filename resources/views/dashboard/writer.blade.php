@extends('layouts.dashboard', [
    'title' => 'Author Studio',
    'subtitle' => 'Write, track, and refine your next chapter in one focused workspace.'
])

@section('dashboard-content')
<div class="pb-10">
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
        <section class="rounded-[28px] border border-neutral-800 bg-[#101418] p-4 sm:p-5 shadow-[0_18px_60px_rgba(2,6,23,0.38)]">
            <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-neutral-700 bg-neutral-900 text-neutral-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" /><circle cx="12" cy="12" r="9" /></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-medium uppercase tracking-[0.24em] text-neutral-500">Current Draft</p>
                        <h2 class="text-sm font-semibold text-white">Moonlit Tides: Book II</h2>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <span class="rounded-full border border-neutral-700 bg-neutral-900 px-2.5 py-1 text-[10px] font-medium uppercase tracking-[0.22em] text-neutral-300">Draft 68%</span>
                    <span class="rounded-full border border-emerald-700/40 bg-emerald-500/10 px-2.5 py-1 text-[10px] font-medium uppercase tracking-[0.22em] text-emerald-300">Live</span>
                </div>
            </div>

            <div class="rounded-[24px] border border-neutral-800 bg-[#0b0f14] p-3 sm:p-4">
                <div class="mb-3 flex items-center justify-between px-2 text-[10px] font-medium uppercase tracking-[0.24em] text-neutral-500">
                    <span>Manuscript</span>
                    <span>2,134 words</span>
                </div>

                <div class="rounded-[22px] border border-neutral-800 bg-[#111821] p-4 sm:p-6">
                    <div class="mb-4 inline-flex rounded-full border border-neutral-700 bg-neutral-900/80 px-3 py-1.5 text-[10px] font-medium uppercase tracking-[0.22em] text-neutral-300">
                        Chapter 12: The Hidden Portal
                    </div>

                    <div class="min-h-[430px] pt-2">
                        <textarea aria-label="Writing workspace" class="w-full min-h-[360px] resize-none border-0 bg-transparent text-[1.04rem] leading-8 text-neutral-200 placeholder:text-neutral-500 focus:outline-none" style="font-family: Georgia, 'Times New Roman', serif;" spellcheck="false">The stone door breathed once beneath the moonlit arch, and the damp air of the cavern shifted around her like a whispered secret. Mara pressed her palm to the black seam, and every memory she had carried through the northern roads rushed back at once—her mother’s lullaby, the silver sigil on her wrist, the promise sworn beneath winter stars. Beyond the threshold, darkness waited with patient eyes.

She stepped through without looking back, and the world folded inward with a sound like a closed book. The passage opened into a cathedral of roots and ice, where the ceiling shimmered with phosphorescent vines and every breath left a pale mist in the air. Somewhere deep within the silence, an old voice was calling her name.

The portal had not opened by accident. It had opened because something in the kingdom had begun to remember what it had lost.</textarea>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between px-2">
                    <div class="flex items-center gap-2 text-[10px] font-medium uppercase tracking-[0.22em] text-neutral-500">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-400"></span>
                        Saved automatically
                    </div>
                    <div class="rounded-full border border-neutral-700 bg-neutral-900 px-3 py-1.5 text-xs font-medium text-neutral-200 tabular-nums">
                        2,134 words
                    </div>
                </div>
            </div>
        </section>

        <aside class="rounded-[28px] border border-neutral-800 bg-[#0f1419] p-4 sm:p-5 shadow-[0_18px_50px_rgba(2,6,23,0.32)]">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-medium uppercase tracking-[0.25em] text-neutral-500">Story Compass</p>
                    <h3 class="mt-1 text-lg font-semibold text-white">World Notes</h3>
                </div>
                <button class="rounded-full border border-neutral-700 bg-neutral-900 px-2.5 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-neutral-300">Focus</button>
            </div>

            <div class="space-y-4">
                <div class="rounded-2xl border border-neutral-800 bg-[#121a22] p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-[10px] font-medium uppercase tracking-[0.24em] text-neutral-500">Character Profiles</p>
                        <span class="text-[10px] font-medium text-sky-300">+2</span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3 rounded-xl border border-neutral-800 bg-[#0d141b] p-2.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-stone-200 to-neutral-400 text-xs font-semibold text-neutral-900">MV</div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-white">Mara Voss</p>
                                <p class="text-[11px] text-neutral-400">Relic bearer</p>
                            </div>
                            <span class="rounded-full border border-neutral-700 bg-neutral-900 px-2 py-0.5 text-[9px] font-medium uppercase tracking-[0.18em] text-neutral-300">Core</span>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border border-neutral-800 bg-[#0d141b] p-2.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-slate-300 to-slate-500 text-xs font-semibold text-neutral-900">AR</div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-white">Aurel Rook</p>
                                <p class="text-[11px] text-neutral-400">Gatekeeper</p>
                            </div>
                            <span class="rounded-full border border-neutral-700 bg-neutral-900 px-2 py-0.5 text-[9px] font-medium uppercase tracking-[0.18em] text-neutral-300">Foil</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-neutral-800 bg-[#121a22] p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-[10px] font-medium uppercase tracking-[0.24em] text-neutral-500">Outline</p>
                        <span class="text-[10px] font-medium text-neutral-300">3 acts</span>
                    </div>

                    <div class="space-y-3">
                        <div class="rounded-xl border border-neutral-800 bg-[#0d141b] p-3">
                            <p class="mb-1 text-[10px] font-medium uppercase tracking-[0.22em] text-neutral-300">Act I</p>
                            <p class="text-sm leading-relaxed text-neutral-300">The hidden portal awakens beneath the cathedral, forcing Mara to choose between memory and destiny.</p>
                        </div>
                        <div class="rounded-xl border border-neutral-800 bg-[#0d141b] p-3">
                            <p class="mb-1 text-[10px] font-medium uppercase tracking-[0.22em] text-neutral-400">Act II</p>
                            <p class="text-sm leading-relaxed text-neutral-400">Aurel reveals the kingdom’s first fracture, and the past starts rewriting itself.</p>
                        </div>
                        <div class="rounded-xl border border-neutral-800 bg-[#0d141b] p-3">
                            <p class="mb-1 text-[10px] font-medium uppercase tracking-[0.22em] text-neutral-400">Act III</p>
                            <p class="text-sm leading-relaxed text-neutral-400">The final seal breaks, and Mara must become the key that keeps the world whole.</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
