<div id="novel-hover-card" 
     x-data="{ 
        show: false, 
        novel: {}, 
        x: 0, 
        y: 0,
        loading: false,
        timer: null,
        init() {
            window.addEventListener('novel-hover-show', (e) => {
                clearTimeout(this.timer);
                this.timer = setTimeout(() => {
                    this.fetchNovel(e.detail.id);
                    this.x = e.detail.x;
                    this.y = e.detail.y;
                    this.show = true;
                }, 300);
            });
            window.addEventListener('novel-hover-hide', () => {
                clearTimeout(this.timer);
                this.show = false;
            });
        },
        async fetchNovel(id) {
            this.loading = true;
            try {
                const response = await fetch(`/api/novel-details/${id}`);
                this.novel = await response.json();
            } catch (e) {
                console.error('Failed to fetch novel details');
            } finally {
                this.loading = false;
            }
        }
     }"
     x-show="show"
     x-cloak
     @mouseenter="show = true"
     @mouseleave="show = false"
     :style="`position: fixed; left: ${x}px; top: ${y}px; z-index: 9999; transform: translate(10px, -50%);`"
     class="w-72 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden pointer-events-auto transition-all duration-200">
    
    <div x-show="loading" class="p-8 flex items-center justify-center">
        <svg class="animate-spin h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    </div>

    <div x-show="!loading" class="flex flex-col">
        <div class="h-32 relative overflow-hidden">
            <img :src="novel.cover_image_url || '/error.png'" class="w-full h-full object-cover blur-sm opacity-50">
            <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-slate-900 to-transparent"></div>
            <div class="absolute bottom-3 left-4 right-4 flex gap-3 items-end">
                <img :src="novel.cover_image_url || '/error.png'" class="w-16 h-24 rounded-lg shadow-lg border border-white dark:border-slate-800 object-cover shrink-0">
                <div class="min-w-0 pb-1">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1" x-text="novel.title"></h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400" x-text="novel.author_name"></p>
                </div>
            </div>
        </div>
        
        <div class="p-4 pt-2">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex items-center gap-1 text-amber-500 text-xs font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    <span x-text="novel.rating_avg"></span>
                </div>
                <div class="flex items-center gap-1 text-slate-500 text-[10px] font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span x-text="novel.view_count"></span>
                </div>
                <div class="flex items-center gap-1 text-slate-500 text-[10px] font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                    <span x-text="novel.bookmarks_count"></span>
                </div>
            </div>

            <p class="text-[11px] text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed mb-4" x-text="novel.description"></p>
            
            <div class="flex flex-wrap gap-1.5">
                <template x-for="genre in (novel.genres || [])" :key="genre.id">
                    <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-[9px] font-bold text-slate-500 dark:text-slate-400 rounded-md border border-slate-200 dark:border-slate-700" x-text="genre.name"></span>
                </template>
            </div>
        </div>
    </div>
</div>
