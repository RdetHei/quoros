<div x-data="cookieConsent()" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="fixed bottom-4 left-4 right-4 md:left-auto md:right-8 md:w-96 z-[100]"
     x-cloak>
    <div class="bg-slate-900/95 backdrop-blur-md border border-slate-800 rounded-2xl p-6 shadow-2xl">
        <div class="flex items-start gap-4">
            <div class="p-2 bg-indigo-500/10 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-white font-semibold mb-1">Persetujuan Cookie</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Kami menggunakan cookie untuk meningkatkan pengalaman Anda di situs kami. Dengan mengklik "Terima", Anda menyetujui penyimpanan cookie selama 1 hari.
                </p>
                <div class="mt-4 flex gap-3">
                    <button @click="accept()" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors">
                        Terima Semua
                    </button>
                    <button @click="show = false" 
                            class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-medium rounded-xl transition-colors">
                        Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function cookieConsent() {
        return {
            show: false,
            init() {
                if (!this.getCookie('cookie_consent')) {
                    setTimeout(() => {
                        this.show = true;
                    }, 1000);
                }
            },
            accept() {
                this.setCookie('cookie_consent', 'accepted', 1);
                this.show = false;
            },
            setCookie(name, value, days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                const expires = "; expires=" + date.toUTCString();
                document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=Lax";
            },
            getCookie(name) {
                const nameEQ = name + "=";
                const ca = document.cookie.split(';');
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }
        }
    }
</script>
