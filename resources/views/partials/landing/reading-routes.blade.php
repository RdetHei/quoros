<section style="padding:2.5rem 0 1rem;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2 mb-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="showcase-eyebrow">Start here</p>
                <h2 class="mt-2 text-xl font-black tracking-tight text-white sm:text-2xl">Choose your reading route.</h2>
            </div>
            <p class="max-w-sm text-xs leading-relaxed text-neutral-500">Tiga cara sederhana untuk menemukan bacaan berikutnya.</p>
        </div>
        <div class="grid gap-3 md:grid-cols-3">
            <a href="{{ route('novels.updated') }}" class="reading-route">
                <span class="reading-route-index">01 / FRESH</span>
                <div>
                    <h3 class="reading-route-title">Yang baru datang</h3>
                    <p class="reading-route-copy">Ikuti chapter dan novel yang baru diperbarui minggu ini.</p>
                </div>
                <span class="reading-route-arrow">&rarr;</span>
            </a>
            <a href="{{ route('welcome') }}" class="reading-route">
                <span class="reading-route-index">02 / CURATED</span>
                <div>
                    <h3 class="reading-route-title">Pilihan yang terkurasi</h3>
                    <p class="reading-route-copy">Masuk ke katalog utama dan temukan cerita sesuai ritmemu.</p>
                </div>
                <span class="reading-route-arrow">&rarr;</span>
            </a>
            <a href="{{ route('genres.index') }}" class="reading-route">
                <span class="reading-route-index">03 / MOOD</span>
                <div>
                    <h3 class="reading-route-title">Mulai dari suasana</h3>
                    <p class="reading-route-copy">Pilih genre ketika kamu sudah tahu rasa cerita yang dicari.</p>
                </div>
                <span class="reading-route-arrow">&rarr;</span>
            </a>
        </div>
    </div>
</section>
