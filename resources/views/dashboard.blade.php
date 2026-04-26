@extends('layouts.app')

@section('content')
<div class="space-y-10 pb-10" x-data="{ activeTab: 'bookmarks' }">
    <!-- Header Dinamis & Stats -->
    <header class="relative overflow-hidden bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">
                    Selamat {{ \Carbon\Carbon::now()->hour < 12 ? 'Pagi' : (\Carbon\Carbon::now()->hour < 18 ? 'Siang' : 'Malam') }}, 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">{{ $user->name }}!</span>
                </h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium">Senang melihatmu kembali. Siap melanjutkan petualangan hari ini?</p>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center px-4 py-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jam Baca</p>
                    <p class="text-lg font-black text-indigo-600 dark:text-indigo-400">{{ $totalReadingHours }}h</p>
                </div>
                <div class="text-center px-4 py-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Favorit</p>
                    <p class="text-lg font-black text-violet-600 dark:text-violet-400 line-clamp-1" title="{{ $favoriteNovel->title ?? '-' }}">
                        {{ $favoriteNovel ? count($user->bookmarks) : '-' }}
                    </p>
                </div>
                <div class="text-center px-4 py-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Koin</p>
                    <p class="text-lg font-black text-amber-500">{{ $userPoints }}</p>
                </div>
            </div>
        </div>
        
        <!-- Background Decoration -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-violet-600/5 rounded-full blur-3xl"></div>
    </header>

    <!-- Hero Section: Lanjutkan Membaca -->
    @if($lastRead)
    <section class="relative group overflow-hidden rounded-[2.5rem] bg-slate-900 text-white min-h-[300px] flex items-center shadow-2xl shadow-indigo-200 dark:shadow-none">
        <!-- Cover Background Blur -->
        <div class="absolute inset-0 opacity-30 grayscale group-hover:grayscale-0 transition-all duration-700">
            <img src="{{ asset('storage/' . $lastRead->novel->cover_image) }}" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/80 to-transparent"></div>

        <div class="relative z-10 p-8 md:p-12 flex flex-col md:flex-row items-center gap-10 w-full">
            <div class="w-32 md:w-44 flex-shrink-0 shadow-2xl rounded-2xl overflow-hidden transform group-hover:scale-105 transition-transform duration-500">
                <img src="{{ asset('storage/' . $lastRead->novel->cover_image) }}" class="w-full h-full object-cover aspect-[3/4]">
            </div>
            
            <div class="flex-grow space-y-6">
                <div>
                    <span class="inline-block px-4 py-1.5 bg-indigo-600/20 backdrop-blur-md border border-indigo-500/30 rounded-full text-indigo-400 text-xs font-bold uppercase tracking-widest mb-4">Lanjutkan Membaca</span>
                    <h2 class="text-3xl md:text-4xl font-black text-white mb-2 leading-tight">{{ $lastRead->novel->title }}</h2>
                    <p class="text-slate-400 font-medium italic">Sampai di: {{ $lastRead->chapter->title }}</p>
                </div>

                <div class="max-w-md">
                    <div class="flex justify-between items-end mb-2">
                        <p class="text-xs font-bold text-slate-400 uppercase">Progress</p>
                        <p class="text-sm font-black text-indigo-400">{{ $lastRead->progress }}% <span class="text-slate-500 text-[10px] font-medium uppercase ml-1">({{ $lastRead->current_pos }}/{{ $lastRead->total_chapters }} Chapter)</span></p>
                    </div>
                    <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-600 to-violet-600 rounded-full transition-all duration-1000" style="width: {{ $lastRead->progress }}%"></div>
                    </div>
                </div>

                <a href="{{ route('chapters.show', [$lastRead->novel->slug, $lastRead->chapter->slug]) }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-slate-900 font-black rounded-2xl hover:bg-indigo-50 transition-all transform hover:-translate-y-1 shadow-xl">
                    Baca Sekarang
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Statistik Penulis (Conditional) -->
            @if($writerStats)
            <section class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        Statistik Penulis
                    </h2>
                    <div class="flex items-center gap-4">
                        @if($user->role === 'admin')
                            <a href="{{ route('admin.requests.index') }}" class="text-sm font-bold text-amber-600 hover:text-amber-700 transition-colors flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                Request Novel
                            </a>
                        @endif
                        <a href="{{ route('writer.novels.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 transition-colors">Kelola Karya &rarr;</a>
                    </div>
                </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group hover:border-violet-500/30 transition-all">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Total Views</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($writerStats['total_views']) }}</h3>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group hover:border-violet-500/30 transition-all">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Ulasan</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $writerStats['total_comments'] }}</h3>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group hover:border-violet-500/30 transition-all">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Avg Rating</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($writerStats['avg_rating'], 1) }}<span class="text-sm text-amber-500 ml-1">★</span></h3>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group hover:border-violet-500/30 transition-all">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Karya</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $writerStats['novel_count'] }}</h3>
            </div>
        </div>
    </section>
    @endif

    <!-- Tabbed Content Section -->
    <section class="space-y-8">
        <div class="flex items-center gap-2 p-1 bg-slate-100 dark:bg-slate-800/50 rounded-2xl w-fit">
            <button @click="activeTab = 'bookmarks'" :class="activeTab === 'bookmarks' ? 'bg-white dark:bg-slate-900 text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-6 py-2.5 text-sm font-bold rounded-xl transition-all">
                Bookmark
            </button>
            <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'bg-white dark:bg-slate-900 text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-6 py-2.5 text-sm font-bold rounded-xl transition-all">
                Riwayat
            </button>
            <button @click="activeTab = 'recommendations'" :class="activeTab === 'recommendations' ? 'bg-white dark:bg-slate-900 text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-6 py-2.5 text-sm font-bold rounded-xl transition-all">
                Rekomendasi
            </button>
            <button @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'bg-white dark:bg-slate-900 text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-6 py-2.5 text-sm font-bold rounded-xl transition-all">
                Pengaturan Profil
            </button>
        </div>

        <!-- Bookmark Tab -->
        <div x-show="activeTab === 'bookmarks'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
                @forelse($bookmarks as $bookmark)
                    <a href="{{ route('novels.show', $bookmark->novel->slug) }}" class="group space-y-3">
                        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden shadow-sm group-hover:shadow-xl group-hover:shadow-indigo-100 dark:group-hover:shadow-none transition-all duration-500">
                            <img src="{{ asset('storage/' . $bookmark->novel->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $bookmark->novel->title }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $bookmark->novel->author->name }}</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-20 text-center space-y-4 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-slate-800">
                        <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                        </div>
                        <p class="text-slate-500 font-medium">Belum ada novel yang dibookmark.</p>
                        <a href="{{ route('home') }}" class="inline-block text-sm font-bold text-indigo-600 hover:underline">Cari Novel &rarr;</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- History Tab -->
        <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm divide-y divide-slate-50 dark:divide-slate-800/50">
                @forelse($histories as $history)
                    <div class="flex items-center gap-6 p-6 hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all first:rounded-t-[2.5rem] last:rounded-b-[2.5rem]">
                        <div class="w-12 h-16 flex-shrink-0 rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ asset('storage/' . $history->novel->cover_image) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="font-bold text-slate-900 dark:text-white truncate">{{ $history->novel->title }}</h4>
                            <p class="text-xs text-slate-500 font-medium">{{ $history->chapter->title }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ $history->updated_at->diffForHumans() }}</p>
                            <a href="{{ route('chapters.show', [$history->novel->slug, $history->chapter->slug]) }}" class="inline-flex px-4 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-indigo-600 hover:text-white transition-all">Lanjut</a>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center space-y-4">
                        <p class="text-slate-500 font-medium">Belum ada riwayat bacaan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recommendations Tab -->
        <div x-show="activeTab === 'recommendations'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recommendations as $novel)
                    <a href="{{ route('novels.show', $novel->slug) }}" class="group bg-white dark:bg-slate-900 p-4 rounded-[2rem] border border-slate-100 dark:border-slate-800 flex gap-4 hover:border-indigo-500/30 hover:shadow-xl transition-all">
                        <div class="w-24 h-32 flex-shrink-0 rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow flex flex-col justify-between py-1">
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $novel->title }}</h4>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-xs font-bold text-amber-500">{{ number_format($novel->rating_avg, 1) }}</span>
                                    <div class="flex text-amber-400 text-[10px]">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $i < floor($novel->rating_avg) ? 'fill-current' : 'text-slate-200 dark:text-slate-700' }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                @foreach($novel->genres->take(2) as $genre)
                                    <span class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800 text-slate-400 text-[10px] font-bold rounded-md uppercase tracking-widest">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Settings Tab -->
        <div x-show="activeTab === 'settings'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm p-8 md:p-12">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Foto Profil</label>
                                <div class="flex items-center gap-6">
                                    <div class="w-24 h-24 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl font-black text-slate-300 uppercase">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" name="profile_photo" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Username</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">@</span>
                                    <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                                        class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                                        placeholder="username_kamu">
                                </div>
                                @error('username') <p class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Bio</label>
                                <textarea name="bio" rows="4" 
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all resize-none"
                                    placeholder="Ceritakan sedikit tentang dirimu...">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio') <p class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                                <h4 class="font-bold text-slate-900 dark:text-white mb-4">Privasi Daftar Bacaan</h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600 dark:text-slate-400">Tampilkan daftar bacaan di profil publik?</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="is_public_reading_list" value="0">
                                        <input type="checkbox" name="is_public_reading_list" value="1" class="sr-only peer" {{ $user->is_public_reading_list ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">
                                    Simpan Perubahan
                                </button>
                                <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="block text-center mt-4 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors">
                                    Lihat Profil Publik &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
