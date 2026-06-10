@extends('layouts.writer', [
    'title' => 'Novel Identity',
    'subtitle' => 'Step 1: Define the core details of your story.'
])

@section('content')
<div class="space-y-8">
    @include('writer.novels._wizard-steps', ['currentStep' => 1])

    <!-- Submission Guidelines -->
    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden group shadow-xl shadow-indigo-500/20">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-colors"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-8">
            <div class="shrink-0 p-4 bg-white/10 rounded-[2rem] border border-white/20 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-black uppercase tracking-tight">Submission Protocol & Guidelines</h3>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex items-start gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-300 mt-2 shrink-0"></div>
                        <p class="text-[11px] leading-relaxed text-indigo-100 italic">
                            <strong class="text-white">Manual Review:</strong> All new works undergo a thorough review by the Quoros Team to ensure catalog quality and safety.
                        </p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-rose-400 mt-2 shrink-0"></div>
                        <p class="text-[11px] leading-relaxed text-indigo-100 italic">
                            <strong class="text-white">Strict Prohibitions:</strong> Sexually explicit imagery, pornographic content, and privacy violations will result in immediate rejection and account suspension.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-sm">
        <form action="{{ route('writer.novels.store.step-1') }}" method="POST" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Judul -->
                <div class="space-y-2">
                    <label for="title" class="text-xs font-black uppercase tracking-widest text-slate-400">Novel Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $novel->title ?? '') }}" 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white"
                        placeholder="Enter an catchy title..." required>
                    @error('title') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Judul Alternatif -->
                <div class="space-y-2">
                    <label for="alternative_title" class="text-xs font-black uppercase tracking-widest text-slate-400">Alternative Title</label>
                    <input type="text" name="alternative_title" id="alternative_title" value="{{ old('alternative_title', $novel->alternative_title ?? '') }}" 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white"
                        placeholder="Optional...">
                </div>

                <!-- Tipe -->
                <div class="space-y-2">
                    <label for="type" class="text-xs font-black uppercase tracking-widest text-slate-400">Content Type</label>
                    <select name="type" id="type" 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white" required>
                        <option value="original" {{ old('type', $novel->type ?? '') === 'original' ? 'selected' : '' }}>Original Story</option>
                        <option value="web_novel" {{ old('type', $novel->type ?? '') === 'web_novel' ? 'selected' : '' }}>Web Novel</option>
                        <option value="light_novel" {{ old('type', $novel->type ?? '') === 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                    </select>
                    @error('type') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div class="space-y-2">
                    <label for="status" class="text-xs font-black uppercase tracking-widest text-slate-400">Status</label>
                    <select name="status" id="status" 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white" required>
                        <option value="ongoing" {{ old('status', $novel->status ?? '') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="hiatus" {{ old('status', $novel->status ?? '') === 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                        <option value="complete" {{ old('status', $novel->status ?? '') === 'complete' ? 'selected' : '' }}>Complete</option>
                    </select>
                    @error('status') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Content Rating -->
                <div class="space-y-2">
                    <label for="content_rating" class="text-xs font-black uppercase tracking-widest text-slate-400">Content Rating</label>
                    <select name="content_rating" id="content_rating" 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white" required>
                        <option value="everyone" {{ old('content_rating', $novel->content_rating ?? '') === 'everyone' ? 'selected' : '' }}>Everyone</option>
                        <option value="teen" {{ old('content_rating', $novel->content_rating ?? '') === 'teen' ? 'selected' : '' }}>Teen (13+)</option>
                        <option value="mature" {{ old('content_rating', $novel->content_rating ?? '') === 'mature' ? 'selected' : '' }}>Mature (18+)</option>
                    </select>
                    @error('content_rating') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Bahasa -->
                <div class="space-y-2">
                    <label for="language" class="text-xs font-black uppercase tracking-widest text-slate-400">Language</label>
                    <select name="language" id="language" 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white" required>
                        <option value="id" {{ old('language', $novel->language ?? '') === 'id' ? 'selected' : '' }}>Indonesian</option>
                        <option value="en" {{ old('language', $novel->language ?? '') === 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>

                <!-- Region -->
                <div class="space-y-2">
                    <label for="region" class="text-xs font-black uppercase tracking-widest text-slate-400">Content Region</label>
                    <select name="region" id="region" 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white" required>
                        <option value="lokal" {{ old('region', $novel->region ?? '') === 'lokal' ? 'selected' : '' }}>Lokal</option>
                        <option value="global" {{ old('region', $novel->region ?? '') === 'global' ? 'selected' : '' }}>Global</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('writer.novels.index') }}" class="px-8 py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" class="px-10 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">
                    Next Step
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
