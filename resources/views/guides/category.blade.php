@php
    $useWriterShell = auth()->check() && auth()->user()->role === 'writer';
@endphp

@extends($useWriterShell ? 'layouts.writer' : 'layouts.app', [
    'title' => $useWriterShell ? 'How to Write · Chapterly' : '9 Langkah Terbukti Menulis Novel dari Nol · Chapterly by Quoros',
    'subtitle' => 'Bagaimana menghasilkan ide hingga novel siap terbit dan berpenghasilan.',
])

@if(!$useWriterShell)
    @push('head_meta')
    <style>
        html { scroll-behavior: smooth; }
        :target { scroll-margin-top: 5rem; }
        @media (min-width: 1024px) {
            :target { scroll-margin-top: 6.5rem; }
        }
    </style>
    @endpush
@endif

@section($useWriterShell ? 'content' : 'content')
@php
$steps = [
    [
        'num' => '01',
        'title' => 'Siapkan rumah untuk ide-idemu penulum',
        'lede' => 'Pilih secara sengaja apa kualias tulisan incaran. Bekerja skill, kesadaran. Buku adil, memukau pembaca, cara, dan lain sehingga. Menghasilkan menandakan keakian, terbiasa. Menemukan cara yang menghidupkan kerangka, dan kemampuan memetakan imginatif seperti penulis, cara yang menghidupkan kreatifitas menghasilkan.',
        'body' => 'Buat peta secara terstruktur tentang halaman baru. Doa apa saja yang ingin kamu ajak pembaca menikmati. Naskah matang disamakan dengan lingkungan. Kembangkan, terapi hasil pengetesan secara terus. Jangan membuat catatan sebelum memulainya. Kamu dapat berinteraksi dengan cara meneliti, meguji, atau pun menelaah secara mandiri atas topik yang dipilih.',
        'quote' => [
            'text' => 'Cerita hebat dimulai dari satu catatan yang tidak berani dihapuskan. Jangan lepaskan momen ketika respons emosional pertama muncul.',
            'by' => '— Chetan Bhagat',
        ],
    ],
    [
        'num' => '02',
        'title' => 'Temukan ide, genre, dan calon pembacamu',
        'lede' => 'Mengabadikan pengalaman yang tersimpan di alam bawah. Penceritaan, kehidupan, rasa sakit, keberhasilan, kesenangan bersejarah, tempat. Cerita-cerita kecil, kebiasaan, atau ritual, sejarah keluarga, dan segala kenangan yang berharga. Pahami bagaimana cara membaca data real market ketika memilih genre & target usia pembaca.',
        'body' => 'Tentukan genre utama dan satelit yang mengelilinginya. Market mengarahkan ekspektasi pembaca terhadap keunikan cerita kamu sekaligus memudahkan calon pembaca yang tepat menemukan ceritamu lewat tag, katalog, dan keyword pencarian. Bangun kisi kisi calon pembaca ideal: usia, jenis kelamin, ketertarikan, topik apa yang sering mereka baca, dan kapan mereka paling sering membaca.',
        'quote' => [
            'text' => 'Kalau belum yakin genre yang cocok untuk cerita kamu — ceritakan dulu ke temen terdekatmu.',
            'by' => '— Lathief Prakoso, penulis bestseller Jakarta Sebelum Pagi',
        ],
    ],
    [
        'num' => '03',
        'title' => 'Ubah ide menjadi premis dan konflik',
        'lede' => 'Ide selalu berada premis dan menyelesaikan secara terperinci. Premis adalah satu kalimat yang berisi: tokoh utama, impian, rintangan terbesar, dan tarikan terbesar. Dasar premis kuat = novel kuat dari awal, pertengahan yang tidak menggantung, dan akhir yang memuaskan.',
        'body' => 'Buat karakter yang memiliki hasrat (inginan). Berikan rintangan. Berikan tantangan. Kamu tidak perlu menuliskan latar belakang karaktermu sedalam-dalamnya sekarang — cukup, tau arah kuat mereka. Tulis 1 kalimat log line. Tulis 3 paragraf premis. Tanya ke diri sendiri: apa yang dipertaruhkan? Taruhan terbesar? Apa yang karakter dapat (dan hilang) di akhir cerita?',
        'quote' => [
            'text' => 'Secara umum inspirasi memuncuk terus dari karakter yang lebih kuat meninggalkan hasrat menyala dan batas kemampuan tertinggi darinya. Setiap tokoh penentuan, menetapkan pengarah yang menyebabkan pembaca tidak bisa berhenti membaca.',
            'by' => '— Andrea Hirata, Laskar Pelangi',
        ],
    ],
    [
        'num' => '04',
        'title' => 'Kenali karakter, lalu susun peta perjalanan',
        'lede' => 'Buat latar belakang, kebiasaan, pola ketakutan sang tokoh. Gairah-kompaknya, kecewa yang menyengsarakan wajah, tawa spontanitasnya, latar belakang keluarga dan pendidikannya. Apa rahasia terbesar tokoh utama yang tidak ingin pembaca ketahui di bab pertama?',
        'body' => 'Gunakan archetype dan bumbu kompleksitas. Hubungan masing-masing tokoh. Tulis: hubungan kekeluargaan, cinta, persahabatan, benci, kesalingtergantungan, kebetulan. Lalu susun peta perjalanan — starting line (pintu masuk cerita), turning point 1, middle point, turning point 2 (titik terendah), dan climax. Jangan lupakan peta perubahan emosional (arc) masing-masing karakter utama.',
        'quote' => [
            'text' => 'Yang mengerankan sebuah tokoh: ia selalu bertindak + ia selalu berkonsekuensi. Apapun yang diambil, keputusan yang ia pilih punya konsekuensi. Tambahkan ini ke semua karaktermu.',
            'by' => '— Dee Lestari',
        ],
    ],
];

$writeSteps = [
    [
        'num' => '05',
        'title' => 'Tulis bab pertama yang mengundang rasa ingin tahu',
        'lede' => 'Mulai bukan dari latar belakang tokoh utamanya. Tulis momen ketika ada sesuatu yang HILANG. Sesuatu yang BERUBAH. Atau sesuatu yang HILANG. Bab pertama harus menarik, memancing rasa ingin tahu pembaca sampai ke halaman berikutnya. Gunakan teknik: scene in media res, kalimat pertama yang memukul, karakter dengan kejutan.',
        'body' => 'Pikirkan adegan pembuka kisahmu. Tidak usah mulai dari latar belakang keluarga, sejarah kota, atau daftar sifat karakter. LANGSUNG mulai dengan kejadian. Sebuah kado yang datang tanpa alamat, panggilan telepon tengah malam yang selalu hangus, atau seseorang menghilang. Jangan sampai pembaca keluar dari bab pertama tanpa mau tahu lanjutannya.',
        'excerpt' => [
            'chapter' => 'Bab 1 — Surat yang Datang Terlambat',
            'p1' => 'Pak tua Tua Soegi kembali. Nur mengangguk pelan menuju arah meja kayu bundar. Buku-buku goyeng-goyang sudah itu. Jendela lantai dua. Diantara buku-buku lama, ada selembar kertas dengan cap pos sudah aus. Jatuhnya tepat di depan kaki Nur.',
            'p2' => 'Di bagian depan amplop tertulis namanya, dengan tinta hitam yang mulai memudar. Tulisan tangan ayah kandungnya, dengan goresan yang khas — ada goresan miring yang selalu Nur kenali. Bulan sabit di sudut kanan atas.',
            'p3' => 'Ntar membukanya di pinggir lorong. Bawa menyebelah sebelah kiri. Dia berhenti sejenak lalu bersin. Diperhatikan dengan jari gemetar ia membuka lembaran itu, satu per satu. Membaca sepatah dua patah kalimat: Surat ini untukmu, andai aku masih sempat mengirimkannya ketika jantung ini masih berdetak.',
            'p4' => 'Mata berkaca-kaca memegang-n surat tersebut dengan gemetar. Lalu ia memutuskan untuk duduk, menarik nafas panjang, dan membaca semuanya perlahan.',
            'meta' => '500 words · 1st person POV · Prologue → Bab 1 hook: disalibkan secara tiba-tiba. Jika karakter berhenti, karena pemikiran, tawa, penyesalan. Tarikan perhatiannya — selesai.',
        ],
    ],
    [
        'num' => '06',
        'title' => 'Sunting dari cerita besar ke kalimat kecil',
        'lede' => 'Selesai menulis draft kasar belumlah selesai bercerita. Proses suntinglah yang membuat cerita biasa menjadi cerita pembaca berkesan. Strukturnya sudah rapih → rapikan bab → rapikan paragraf → rapikan kalimat → rapikan kata-kata akhir. Pastikan tidak ada scene, dialog, atau paragraph yang tidak berfungsi memajukan cerita.',
        'body' => 'Periksa struktur keseluruhan: inkiting incident tepat? middle point tepat? low point tepat? climax tidak dipaksakan? Hapus adegan yang hanya memanjang tanpa mengubah keadaan karakter. Jangan takut memotong (kill your darlings). Ganti kata kerja pasif menjadi aktif. Buat dialog terdengar seperti orang benar-benar berbicara. Cek repetisi kata dan kalimat. Pastikan suara narasi konsisten — point-of-view tidak lompat-lompat kecuali memang kamu pilih.',
        'quote' => [
            'text' => 'Tulisan yang bagus bukan ditulis. Ia ditulis ulang, ditulis ulang lagi, lalu ditulis ulang lagi. Lalu ditulis ulang, lalu ditulis lagi, ditulis ulang. Sampai kamu merasa capek dan menyerah — satu sesi lagi.',
            'by' => '— Lisa Mangum',
        ],
    ],
];

$shareSteps = [
    [
        'num' => '07',
        'title' => 'Pilih judul, tulis sinopsis, siapkan sampul',
        'lede' => 'Judul yang kuat singkat, mudah diucapkan, mudah diingat dan menjelaskan suasana hati. Buat beberapa opsi, tanyakan ke temen. Pastikan di platform Quoros belum ada nama kembar persis. Cover yang bagus itu cover yang menyesuaikan tone cerita, teksnya tetap terbaca jelas di ukuran thumbnail 200x300.',
        'body' => 'Unsur unsur sampul: gambar iluastrasi/ foto yang mood-nya sama, judul terbaca jelas, nama penulis jelas, tagline opsional (jangan menumpuk). Satu jenis font cover yang readable. Jangan pilih font yang terlalu dekorasi buat judul kalau platform kecil sulit baca. Buat mockup cover dengan beberapa warna, tes thumbnail di handphone (skala kecil). Buat sinopsis 3 paragraf: paragraf 1 = hook & karakter, paragraf 2 = konflik & rintangan, paragraf 3 = taruhan & teaser akhir.',
        'blocks' => [
            [
                'label' => 'CONTOH SAMPUL & JUDUL',
                'body' => 'Selalu uji coba dengan ukuran thumbnail. Jika kamu memperbesar dan memperkecil, tulisan masih bisa terbaca dengan tanpa perlu memekikkan mata. Pastikan perbandingan visual: 60% suasana gambar, 30% komposisi judul dan subjudul, 10% nama penulis & logo. Hindari terlalu banyak warna cerah yang mencolok mata di mobile.',
            ],
            [
                'label' => 'CONTOH SINPOSIS',
                'body' => 'Seorang gadis biasa, Nura, hidup di kota tua Bandung pada tahun 1998. Satu hari menerima paket berisi surat-surat dari ayahnya yang ia kira sudah meninggal 12 tahun lalu. Setiap surat memberikan petunjuk baru tentang rahasia keluarga, ke mana ayahnya pergi, dan mengapa ia harus menjaga sebuah buku tua berwarna hijau botol yang disembunyikan di loteng rumahnya — sebelum sebuah kelompok misterius menemukannya lebih dulu.',
            ],
        ],
    ],
    [
        'num' => '08',
        'title' => 'Terbitkan dengan teliti, lanjutkan dengan ritme',
        'lede' => 'Sebelum publikasi: judul, sinopsis, genre, tag, file cover, dan status novel. Judul, pastikan format nama chapter, status terbit, chapter pertama dijadwalkan atau tidak, dan kamu publish terlebih dahulu. Periksa 3x sebelum publikasikan. Setelah terbit: buat jadwal rilis ritual mingguan yang kamu PEKAT. Jangan publikasi 10 chapter sehari kemudian menghilang 1 bulan.',
        'body' => 'Sebelum memublikasi, Checklist pra-terbit: Semua paragraf sudah di-rapi. Paragraf pendek, tidak terlalu panjang. Cek typo, EYD, tanda baca. URL benar. Cover ok, thumbnail ok. Semua chapter yang mau publish sudah final. Deskripsi sesuai. Tag keyword minimal 3, maksimal 10. Genre utama sesuai rules kategori. Setelah terbit: konsisten rilis chapter. 2x seminggu rutin lebih bagus dari 12x tiba-tiba lalu hilang. Sematkan link di bio sosial media. Bangun interaksi di kolom komentar.',
        'tip' => 'Catatan untuk penulis pemula: Jangan menunda publikasi 6 bulan hanya karena kamu belum merasa "pantas". Tidak ada penulis yang sempurna. Publish sekarang, lalu perbaiki pengalaman lewat cerita berikutnya.',
    ],
    [
        'num' => '09',
        'title' => 'Bangun percakapan, bukan sekadar angka',
        'lede' => 'Fokus pada keterlibatan komunitas, bukan hanya jumlah pembaca yang tertulis di statistik. Baca ulasan, balas komentar, buat polling karakter, posting mini cerpen bonus, buat playlist lagu tema novel di Spotify, dan sesekali kasih behind the scene process-mu saat menulis.',
        'body' => 'Pembaca itu bukan pelanggan — mereka pendamping. Jangan terlalu serius mengejar angka ranking sampai lupa berbicara dengan manusia yang membaca. Balas komentar dengan cara yang manusiawi, tidak templat. Ajukan pertanyaan ke mereka di akhir bab. Buat sesi QnA rutin. Jika ada kritik yang membangun, terima dengan santai — tidak perlu membalas emosi. Semakin kamu berinteraksi sebagai penulis (bukan brand), semakin mereka setia.',
        'quote' => [
            'text' => 'Cerita akan hidup di hati pembaca lewat ulasan, komentar, dan fanart yang mereka buat. Hargai setiap jejak digital yang tertinggal.',
            'by' => '— Penulis yang memilih nama pena, dan berhenti mencari perhatian.',
        ],
    ],
];

$checklist = [
    ['text' => 'Identitas penulis siap', 'sub' => 'Nama pena, bio singkat, dan cover avatar sudah siap dipublikasikan.'],
    ['text' => 'Judul pilihan siap', 'sub' => 'Alternatif 2-3 judul. Cek duplikat di katalog Quoros.'],
    ['text' => 'Bab pertama sudah ditulis', 'sub' => 'Minimal 3 chapter lengkap & tersimpan sebagai draft.'],
    ['text' => 'Kemas menuliskan ide', 'sub' => 'Premis 1 kalimat, konflik utama, dan taruhan akhir jelas.'],
    ['text' => 'Hal penggambaran karakter', 'sub' => 'Karakter utama, pendukung, dan antagonis sudah teridentifikasi.'],
    ['text' => 'Pelaksanaan platform sudah dikenal', 'sub' => 'Cara submit novel, jadwal rilis, dan benefit pengetahuan monetisasi.'],
    ['text' => 'Perencanaan strategi promosi', 'sub' => 'Minimal punya 3 kontak / komunitas yang bisa membantu share pertama.'],
    ['text' => 'Rencana langkah realistis', 'sub' => 'Target kata per hari, target bab per minggu, target tanggal rilis final.'],
];

$faqs = [
    ['q' => 'Apakah aku harus sudah bergelar penulis?', 'a' => 'Tidak. Satu-satunya persyaratan menjadi penulis adalah menulis secara rutin. Kamu tidak perlu ijazah, pengalaman, atau pelatihan khusus. Hanya butuh cerita yang ingin dibagikan, dan kegigihan menyelesaikannya sampai akhir.'],
    ['q' => 'Berapa panjang bab ideal?', 'a' => 'Tidak ada aturan baku — tapi untuk novel di platform digital, 1 bab antara 1500-3000 kata adalah sweet spot pembaca (waktu baca 6-12 menit). Kalau bisa tulis lebih pendek, tapi pastikan satu bab punya mini arc sendiri (ada perubahan di akhir).'],
    ['q' => 'Haruskah novel selesai sebelum diterbitkan?', 'a' => 'Tidak wajib untuk terbit chapter pertama. Tapi sangat disarankan minimal punya 5 chapter cadangan + outline lengkap sebelum kamu terbitkan episode pertama. Hal ini menghindari kamu writer block di tengah jalan dan kehilangan pembaca.'],
    ['q' => 'Bagaimana kalau genre di tengah jalan berubah?', 'a' => 'Wajar. Perubahan sudut karakter, konflik yang melebar, atau mood penulis yang berubah. Pertimbangkan: apakah perubahan itu memperkaya cerita atau membuat pembaca pusing? Jika 180 derajat rubah genre, mungkin lebih baik simpan cerita itu untuk novel berikutnya.'],
    ['q' => 'Bolehkah aku juga sudah berhadiah sebelum terbit?', 'a' => 'Boleh. Proses kontrak dengan pihak penerbit tradisional belum tentu kamu temui di Quoros. Quoros memprioritaskan penulis independen yang punya hak cipta penuh atas karyanya sendiri. Gunakan hak itu dengan bijak.'],
    ['q' => 'Apa yang membedakan namamu sendiri nggak jelas?', 'a' => 'Ambil satu sisi keunikan: gaya bahasa yang khas, tema yang selalu kamu tulis, struktur ending yang selalu berkebalikan, bahkan cara kamu memberi nama karakter. Konsistenkan itu, dan pembaca akan "mencium" tulisanmu dari 1 kalimat pertama saja.'],
];
@endphp

<div class="{{ $useWriterShell ? 'space-y-10' : 'max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-24' }}">
    @if(!$useWriterShell)
    <nav class="mb-10 flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-neutral-500">
        <a href="{{ route('guides.index') }}" class="hover:text-[#c7a64a] transition-colors">GUIDANCE</a>
        <span>/</span>
        <span class="text-neutral-300">PANDUAN MENULIS</span>
    </nav>
    @endif

    <section class="lg:grid lg:grid-cols-[230px_minmax(0,1fr)] gap-8 items-start">
        @if(!$useWriterShell)
        <aside class="hidden lg:block lg:sticky lg:top-[6.5rem] self-start space-y-6 max-h-[calc(100vh-12rem)] overflow-y-auto pr-3 custom-scrollbar">
            <div class="border border-[#c7a64a]/20 bg-[#c7a64a]/[0.04] rounded-lg p-4 space-y-3">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center h-5 w-5 rounded border border-[#c7a64a]/40 text-[#c7a64a]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    </span>
                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#c7a64a]">Kerja cerdas dimulai dari pondasi.</p>
                </div>
                <p class="text-[11px] leading-relaxed text-neutral-400">9 tahap eksekusi penulis pemula sampai bisa menerbitkan chapter pertama. Tidak perlu takut salah — setiap tahap ada contoh copy paste langsung.</p>
            </div>

            <div>
                <p class="px-3 text-[9px] font-bold uppercase tracking-[0.28em] text-neutral-600 mb-3">Daftar isi / tahap penulisan</p>
                <ul class="space-y-1.5">
                    <li>
                        <a href="#step-01" class="flex items-start gap-3 px-3 py-2 rounded-md border border-[#c7a64a]/25 bg-[#c7a64a]/10 text-[12px] font-semibold text-[#f2efe8]">
                            <span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-[#c7a64a]/40 flex items-center justify-center text-[9px] text-[#c7a64a]">✓</span>
                            <span class="flex flex-col">
                                <span>01 · Buat pondasi</span>
                            </span>
                        </a>
                    </li>
                    <li><a href="#step-02" class="flex items-start gap-3 px-3 py-2 rounded-md text-[12px] font-medium text-neutral-400 hover:bg-white/5 hover:text-[#f2efe8] transition-colors"><span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-neutral-700 text-[9px] text-neutral-500 flex items-center justify-center">02</span><span>Jelajahi genre &amp; ide</span></a></li>
                    <li><a href="#step-03" class="flex items-start gap-3 px-3 py-2 rounded-md text-[12px] font-medium text-neutral-400 hover:bg-white/5 hover:text-[#f2efe8] transition-colors"><span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-neutral-700 text-[9px] text-neutral-500 flex items-center justify-center">03</span><span>Premis &amp; konflik</span></a></li>
                    <li><a href="#step-04" class="flex items-start gap-3 px-3 py-2 rounded-md text-[12px] font-medium text-neutral-400 hover:bg-white/5 hover:text-[#f2efe8] transition-colors"><span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-neutral-700 text-[9px] text-neutral-500 flex items-center justify-center">04</span><span>Bangun karakter</span></a></li>
                    <li><a href="#step-05" class="flex items-start gap-3 px-3 py-2 rounded-md text-[12px] font-medium text-neutral-400 hover:bg-white/5 hover:text-[#f2efe8] transition-colors"><span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-neutral-700 text-[9px] text-neutral-500 flex items-center justify-center">05</span><span>Menyusun plot / menulis bab 1</span></a></li>
                    <li><a href="#step-06" class="flex items-start gap-3 px-3 py-2 rounded-md text-[12px] font-medium text-neutral-400 hover:bg-white/5 hover:text-[#f2efe8] transition-colors"><span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-neutral-700 text-[9px] text-neutral-500 flex items-center justify-center">06</span><span>Sunting &amp; perbaiki</span></a></li>
                    <li><a href="#step-07" class="flex items-start gap-3 px-3 py-2 rounded-md text-[12px] font-medium text-neutral-400 hover:bg-white/5 hover:text-[#f2efe8] transition-colors"><span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-neutral-700 text-[9px] text-neutral-500 flex items-center justify-center">07</span><span>Judul, sinopsis &amp; sampul</span></a></li>
                    <li><a href="#step-08" class="flex items-start gap-3 px-3 py-2 rounded-md text-[12px] font-medium text-neutral-400 hover:bg-white/5 hover:text-[#f2efe8] transition-colors"><span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-neutral-700 text-[9px] text-neutral-500 flex items-center justify-center">08</span><span>Tulis bab pertama</span></a></li>
                    <li><a href="#step-09" class="flex items-start gap-3 px-3 py-2 rounded-md text-[12px] font-medium text-neutral-400 hover:bg-white/5 hover:text-[#f2efe8] transition-colors"><span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-neutral-700 text-[9px] text-neutral-500 flex items-center justify-center">09</span><span>Penerbitan &amp; promosi</span></a></li>
                    <li><a href="#checklist" class="flex items-start gap-3 px-3 py-2 rounded-md text-[12px] font-medium text-neutral-400 hover:bg-white/5 hover:text-[#f2efe8] transition-colors"><span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-dashed border-neutral-700 text-[9px] text-neutral-500 flex items-center justify-center">✓</span><span>Checklist siap terbit</span></a></li>
                    <li><a href="#faq" class="flex items-start gap-3 px-3 py-2 rounded-md text-[12px] font-medium text-neutral-400 hover:bg-white/5 hover:text-[#f2efe8] transition-colors"><span class="mt-[1px] h-4 w-4 shrink-0 rounded-sm border border-dashed border-neutral-700 text-[9px] text-neutral-500 flex items-center justify-center">?</span><span>FAQ penulis pemula</span></a></li>
                </ul>
            </div>

            <div class="border border-neutral-800 bg-[#121212]/80 rounded-lg p-4 space-y-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-neutral-500">Ceklis setiap selesai satu bagian ya ✨</p>
                <p class="text-[11px] leading-relaxed text-neutral-400">Percepatan progress simpan di localstorage dengan klik tombol simpan progres di akhir.</p>
            </div>

            <div class="border border-neutral-800 bg-[#0a0a0a] rounded-lg p-4 space-y-3">
                <p class="text-[9px] font-bold uppercase tracking-[0.22em] text-neutral-600">PERCEPAT PROSESMU</p>
                <div class="space-y-2">
                    <div>
                        <p class="text-[11px] font-semibold text-[#f2efe8]">Sudah jadi ide &amp; novel?</p>
                        <p class="text-[10px] text-neutral-500 leading-snug">Sudah selesaikan 1 draft &amp; cover art sendiri?</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-[#f2efe8]">Sudah jadi cover art?</p>
                        <p class="text-[10px] text-neutral-500 leading-snug">Lewati ke step berikutnya dari dashboard.</p>
                    </div>
                </div>
                <a href="{{ route('writer.novels.create.step-1') }}" class="block w-full text-center py-2.5 border border-[#c7a64a]/40 text-[10px] font-semibold uppercase tracking-[0.22em] text-[#c7a64a] hover:bg-[#c7a64a]/10 rounded-md transition-colors">
                    Mulai di step-02 / jelajahi genre ↗
                </a>
                <p class="text-[9.5px] text-neutral-500 leading-snug">Penting: simpan progress ke localstorage dengan klik simpan progress di akhir.</p>
            </div>
        </aside>
        @endif

        <div class="space-y-16">
            <section class="relative overflow-hidden rounded-[28px] border border-[#c7a64a]/15 bg-gradient-to-br from-[#161208] via-[#0a0a0a] to-[#121212] p-8 md:p-12 @if(!$useWriterShell) lg:p-16 @endif">
                <div class="absolute -top-24 -right-16 w-[380px] h-[380px] rounded-full bg-[#c7a64a]/10 blur-[120px]"></div>
                <div class="absolute -bottom-20 -left-10 w-[300px] h-[300px] rounded-full bg-[#5a4115]/20 blur-[100px]"></div>

                <div class="relative grid md:grid-cols-[minmax(0,1.4fr)_minmax(260px,1fr)] gap-10 items-center">
                    <div class="space-y-7 min-w-0">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.32em] text-[#c7a64a] mb-4">KIKI · 01 PROYEK PERTAMA</p>
                            <h1 class="font-serif text-[46px] md:text-[64px] leading-[1.02] tracking-tight text-[#f2efe8]">
                                Cerita pertamamu dimulai di sini.
                            </h1>
                            <p class="mt-5 text-[15px] md:text-base leading-relaxed text-neutral-400 max-w-xl">
                                Dari sebuah ide menjadi novel yang siap dibaca.
                            </p>
                        </div>

                        <p class="text-[12.5px] leading-[1.85] text-neutral-400 max-w-2xl">
                            <span class="text-[#f2efe8] font-semibold">PILIH SALAH SATU topik utama penulisan novel, dan buat target sesuai ritme kamu.</span>
                            Gunakan checklist, catatan dan semua panduan materi terlampir. Jangan ragu membuka step 1-9 kapanpun. Jika stuck —
                            <span class="text-[#c7a64a] font-semibold">mendiskusikan dengan komunitas penulis di Chapterly Discord — catat masalahnya untuk dirimu sendiri, kemudian lanjutkan satu kata satu kalimat.</span>
                        </p>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            @auth
                                @if(Auth::user()->role === 'writer')
                                    <a href="{{ route('writer.dashboard') }}" class="inline-flex items-center gap-3 px-8 py-3.5 bg-[#c7a64a] hover:bg-[#d4b356] text-black text-[11.5px] font-bold uppercase tracking-[0.22em] rounded-md shadow-lg shadow-[#c7a64a]/20 active:scale-[0.98] transition-all">
                                        Kembali ke dashboard →
                                    </a>
                                @else
                                    <form method="POST" action="{{ route('dashboard.become-writer') }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-3 px-8 py-3.5 bg-[#c7a64a] hover:bg-[#d4b356] text-black text-[11.5px] font-bold uppercase tracking-[0.22em] rounded-md shadow-lg shadow-[#c7a64a]/20 active:scale-[0.98] transition-all">
                                            Mulai prosesmu →
                                        </button>
                                    </form>
                                @endif
                            @endauth
                            @guest
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-8 py-3.5 bg-[#c7a64a] hover:bg-[#d4b356] text-black text-[11.5px] font-bold uppercase tracking-[0.22em] rounded-md shadow-lg shadow-[#c7a64a]/20 active:scale-[0.98] transition-all">
                                    Mulai prosesmu →
                                </a>
                            @endguest
                        </div>
                    </div>

                    <div class="relative mx-auto w-full max-w-[340px]">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/5] border border-[#c7a64a]/20 shadow-2xl shadow-black/80">
                            <div class="absolute inset-0 bg-gradient-to-b from-[#221a0a] via-[#151008] to-[#050403]"></div>
                            <div class="absolute inset-0 flex flex-col justify-between p-6 md:p-8">
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-2 text-[9px] font-bold uppercase tracking-[0.28em] text-[#c7a64a]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>
                                        Draft / In Progress
                                    </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#c7a64a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" /></svg>
                                </div>
                                <div class="space-y-4">
                                    <h3 class="font-serif text-white text-[26px] md:text-[30px] leading-[1.1]">
                                        "Setiap penulis hebat, dulu adalah penulis pemula yang tidak berhenti menulis."
                                    </h3>
                                    <p class="text-[10.5px] font-medium uppercase tracking-[0.22em] text-neutral-500">
                                        — Antonio Tabucchi
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -bottom-3 left-4 right-4 h-8 rounded-b-2xl bg-black/60 blur-2xl"></div>
                    </div>
                </div>
            </section>

            <div class="border-t border-b border-neutral-800 py-3.5 flex flex-wrap items-center justify-between gap-3 text-[10.5px] uppercase tracking-[0.22em]">
                <p class="text-neutral-500 font-semibold">BAB · 01 · CARA MENULIS</p>
                <p class="text-neutral-600">Buat antarmuka penelitian kebagian setiap langkah. Gunakan checklist. Gunakan "catatan khusus Chapterly" untuk mencatat hal-hal yang kamu butuhkan sebagai bahan cerita nanti.</p>
                <p class="text-neutral-500 font-semibold">LAKUKAN SEKARANG</p>
            </div>

            <section class="space-y-14">
                <header class="border-l-2 border-[#c7a64a]/60 pl-5 md:pl-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#c7a64a] mb-2">BAGIAN 1 · BAGAIMANA CARA MENULIS</p>
                    <h2 class="font-serif text-[32px] md:text-[40px] leading-[1.08] text-[#f2efe8]">
                        Bangun fondasi ceritamu.
                    </h2>
                    <p class="mt-3 text-[13.5px] leading-relaxed text-neutral-400 max-w-3xl">
                        Buat pondasi tulisan yang kokoh tanpa hambatan. Onboarding "buatlah pondasi sebelum membangun menara" — pondasi untuk menyusun ide-ide menjadi karya serius.
                    </p>
                </header>

                @foreach($steps as $s)
                    <article id="step-{{ $s['num'] }}" class="grid md:grid-cols-[64px_minmax(0,1fr)] gap-6 items-start scroll-mt-40">
                        <div class="flex items-start">
                            <div class="flex h-11 w-11 items-center justify-center rounded-md border border-[#c7a64a]/25 bg-[#c7a64a]/[0.06] text-[14px] font-bold text-[#c7a64a] tabular-nums tracking-tight">
                                {{ $s['num'] }}
                            </div>
                        </div>
                        <div class="space-y-5 md:space-y-6">
                            <h3 class="font-serif text-[24px] md:text-[28px] leading-tight text-[#f2efe8]">
                                {{ $s['title'] }}
                            </h3>
                            <div class="space-y-4 text-[13.5px] md:text-[14px] leading-[1.9] text-neutral-400">
                                <p>{{ $s['lede'] }}</p>
                                <p>{{ $s['body'] }}</p>
                            </div>
                            @if(isset($s['quote']))
                            <blockquote class="relative border-l border-[#c7a64a]/35 bg-[#c7a64a]/[0.04] rounded-r-lg pl-5 pr-5 py-4 md:py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute -top-2 left-3 h-5 w-5 text-[#c7a64a]/50" fill="currentColor" viewBox="0 0 24 24"><path d="M7.17 6A5.17 5.17 0 002 11.17V18h6.83v-6.83H5.5A1.67 1.67 0 017.17 9.5V6zm10 0A5.17 5.17 0 0012 11.17V18h6.83v-6.83H15.5A1.67 1.67 0 0117.17 9.5V6z"/></svg>
                                <p class="text-[13.5px] md:text-[14px] leading-[1.9] text-neutral-300 italic">
                                    “{{ $s['quote']['text'] }}”
                                </p>
                                <footer class="mt-3 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#c7a64a]">
                                    {{ $s['quote']['by'] }}
                                </footer>
                            </blockquote>
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>

            <div class="border-t border-b border-neutral-800 py-3.5 flex flex-wrap items-center justify-between gap-3 text-[10.5px] uppercase tracking-[0.22em]">
                <p class="text-neutral-500 font-semibold">BAB · 02 · TULIS CERITAMU</p>
                <p class="text-neutral-600">Sekarang pondasimu siap. Saatnya eksekusi. Dari 0 kalimat menjadi 40.000 kata — dan 3 bab pertama draft final.</p>
                <p class="text-neutral-500 font-semibold">EKSEKUSI DRAF</p>
            </div>

            <section class="space-y-14">
                <header class="border-l-2 border-[#c7a64a]/60 pl-5 md:pl-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#c7a64a] mb-2">BAGIAN 2 · TULIS CERITAMU</p>
                    <h2 class="font-serif text-[32px] md:text-[40px] leading-[1.08] text-[#f2efe8]">
                        Beri cerita itu halaman pertamanya.
                    </h2>
                </header>

                @foreach($writeSteps as $s)
                    <article id="step-{{ $s['num'] }}" class="grid md:grid-cols-[64px_minmax(0,1fr)] gap-6 items-start scroll-mt-40">
                        <div class="flex items-start">
                            <div class="flex h-11 w-11 items-center justify-center rounded-md border border-[#c7a64a]/25 bg-[#c7a64a]/[0.06] text-[14px] font-bold text-[#c7a64a] tabular-nums tracking-tight">
                                {{ $s['num'] }}
                            </div>
                        </div>
                        <div class="space-y-5 md:space-y-6">
                            <h3 class="font-serif text-[24px] md:text-[28px] leading-tight text-[#f2efe8]">
                                {{ $s['title'] }}
                            </h3>
                            <div class="space-y-4 text-[13.5px] md:text-[14px] leading-[1.9] text-neutral-400">
                                <p>{{ $s['lede'] }}</p>
                                <p>{{ $s['body'] }}</p>
                            </div>

                            @if(isset($s['excerpt']))
                            <div class="border border-neutral-800 bg-[#050505] rounded-xl overflow-hidden">
                                <div class="px-5 py-3 border-b border-neutral-800 bg-[#0a0a0a] flex items-center justify-between">
                                    <p class="text-[9px] font-bold uppercase tracking-[0.26em] text-neutral-500">Contoh cerpen · BAB 1 TANPA JUDUL PANJANG</p>
                                    <span class="inline-flex items-center gap-1.5 text-[9px] font-semibold uppercase tracking-[0.2em] text-neutral-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0011.378 2H4.5zM10 8a1 1 0 00-1 1v1H8a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        Draft excerpt
                                    </span>
                                </div>
                                <div class="px-6 md:px-8 py-6 md:py-8 space-y-5">
                                    <h4 class="font-serif text-[22px] md:text-[26px] text-[#f2efe8] border-b border-[#c7a64a]/25 pb-3">{{ $s['excerpt']['chapter'] }}</h4>
                                    @foreach(['p1','p2','p3','p4'] as $pk)
                                    <p class="text-[14px] md:text-[15px] leading-[2] text-neutral-300 first-letter:font-serif first-letter:text-[34px] first-letter:md:text-[44px] first-letter:font-bold first-letter:text-[#c7a64a] first-letter:float-left first-letter:mr-2 first-letter:leading-[0.9] first-letter:mt-1">
                                        {{ $s['excerpt'][$pk] }}
                                    </p>
                                    @endforeach
                                </div>
                                <div class="px-6 md:px-8 py-4 border-t border-neutral-800 bg-[#0a0a0a] flex items-center justify-between gap-4 flex-wrap">
                                    <p class="text-[10.5px] font-semibold uppercase tracking-[0.22em] text-neutral-500">Meta bab</p>
                                    <p class="text-[11.5px] leading-relaxed text-neutral-400 max-w-2xl">{{ $s['excerpt']['meta'] }}</p>
                                </div>
                            </div>
                            @endif

                            @if(isset($s['quote']))
                            <blockquote class="relative border-l border-[#c7a64a]/35 bg-[#c7a64a]/[0.04] rounded-r-lg pl-5 pr-5 py-4 md:py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute -top-2 left-3 h-5 w-5 text-[#c7a64a]/50" fill="currentColor" viewBox="0 0 24 24"><path d="M7.17 6A5.17 5.17 0 002 11.17V18h6.83v-6.83H5.5A1.67 1.67 0 017.17 9.5V6zm10 0A5.17 5.17 0 0012 11.17V18h6.83v-6.83H15.5A1.67 1.67 0 0117.17 9.5V6z"/></svg>
                                <p class="text-[13.5px] md:text-[14px] leading-[1.9] text-neutral-300 italic">
                                    “{{ $s['quote']['text'] }}”
                                </p>
                                <footer class="mt-3 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#c7a64a]">
                                    {{ $s['quote']['by'] }}
                                </footer>
                            </blockquote>
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>

            <div class="border-t border-b border-neutral-800 py-3.5 flex flex-wrap items-center justify-between gap-3 text-[10.5px] uppercase tracking-[0.22em]">
                <p class="text-neutral-500 font-semibold">BAB · 03 · BAGIKAN CERITAMU</p>
                <p class="text-neutral-600">Ceritamu sudah selesai separuh perjalanan. Bagian ini menentukan apakah penemuan, pembaca pertama, dan pasar akan bertemu dengan tepat.</p>
                <p class="text-neutral-500 font-semibold">SIAPKAN PENERBITAN</p>
            </div>

            <section class="space-y-14">
                <header class="border-l-2 border-[#c7a64a]/60 pl-5 md:pl-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#c7a64a] mb-2">BAGIAN 3 · BAGIKAN CERITAMU</p>
                    <h2 class="font-serif text-[32px] md:text-[40px] leading-[1.08] text-[#f2efe8]">
                        Siapkan cerita untuk dibagikan.
                    </h2>
                </header>

                @foreach($shareSteps as $s)
                    <article id="step-{{ $s['num'] }}" class="grid md:grid-cols-[64px_minmax(0,1fr)] gap-6 items-start scroll-mt-40">
                        <div class="flex items-start">
                            <div class="flex h-11 w-11 items-center justify-center rounded-md border border-[#c7a64a]/25 bg-[#c7a64a]/[0.06] text-[14px] font-bold text-[#c7a64a] tabular-nums tracking-tight">
                                {{ $s['num'] }}
                            </div>
                        </div>
                        <div class="space-y-5 md:space-y-6">
                            <h3 class="font-serif text-[24px] md:text-[28px] leading-tight text-[#f2efe8]">
                                {{ $s['title'] }}
                            </h3>
                            <div class="space-y-4 text-[13.5px] md:text-[14px] leading-[1.9] text-neutral-400">
                                <p>{{ $s['lede'] }}</p>
                                <p>{{ $s['body'] }}</p>
                            </div>

                            @if(isset($s['blocks']))
                            <div class="grid md:grid-cols-2 gap-4">
                                @foreach($s['blocks'] as $bl)
                                <div class="border border-neutral-800 bg-[#0a0a0a] rounded-xl p-5 space-y-3">
                                    <p class="text-[9px] font-bold uppercase tracking-[0.26em] text-[#c7a64a]">{{ $bl['label'] }}</p>
                                    <p class="text-[12.5px] md:text-[13px] leading-[1.9] text-neutral-300">{{ $bl['body'] }}</p>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            @if(isset($s['tip']))
                            <div class="border border-[#c7a64a]/25 bg-[#c7a64a]/[0.05] rounded-lg px-5 py-4">
                                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-[#c7a64a] mb-1">TIPS PRO TIP</p>
                                <p class="text-[12.5px] md:text-[13px] leading-[1.9] text-neutral-300">{{ $s['tip'] }}</p>
                            </div>
                            @endif

                            @if(isset($s['quote']))
                            <blockquote class="relative border-l border-[#c7a64a]/35 bg-[#c7a64a]/[0.04] rounded-r-lg pl-5 pr-5 py-4 md:py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute -top-2 left-3 h-5 w-5 text-[#c7a64a]/50" fill="currentColor" viewBox="0 0 24 24"><path d="M7.17 6A5.17 5.17 0 002 11.17V18h6.83v-6.83H5.5A1.67 1.67 0 017.17 9.5V6zm10 0A5.17 5.17 0 0012 11.17V18h6.83v-6.83H15.5A1.67 1.67 0 0117.17 9.5V6z"/></svg>
                                <p class="text-[13.5px] md:text-[14px] leading-[1.9] text-neutral-300 italic">
                                    “{{ $s['quote']['text'] }}”
                                </p>
                                <footer class="mt-3 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#c7a64a]">
                                    {{ $s['quote']['by'] }}
                                </footer>
                            </blockquote>
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>

            <div id="checklist" class="border-t border-b border-neutral-800 py-3.5 flex flex-wrap items-center justify-between gap-3 text-[10.5px] uppercase tracking-[0.22em] scroll-mt-40">
                <p class="text-neutral-500 font-semibold">CHECKLIST SEBELUM KLIK TERBIT</p>
                <p class="text-neutral-600">Sebelum publish novel pertama kamu — pastikan semua kotak di bawah ini tercentang.</p>
                <p class="text-neutral-500 font-semibold">SIAP TERBIT · YES / NO</p>
            </div>

            <section class="rounded-[28px] border border-neutral-800 bg-gradient-to-br from-[#111] via-[#0a0a0a] to-[#0d0d0d] p-6 md:p-10 lg:p-12 space-y-8" x-data="{ checked: [], init() { try { const saved = JSON.parse(localStorage.getItem('chapterly_checklist') || '[]'); this.checked = saved; } catch(e) { this.checked = []; } }, toggle(i) { const idx = this.checked.indexOf(i); if(idx >= 0) this.checked.splice(idx,1); else this.checked.push(i); localStorage.setItem('chapterly_checklist', JSON.stringify(this.checked)); }, isChecked(i) { return this.checked.includes(i); } }">
                <header class="border-l-2 border-[#c7a64a]/60 pl-5 md:pl-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#c7a64a] mb-2">CHECKLIST PRA-PENERBITAN</p>
                    <h2 class="font-serif text-[32px] md:text-[40px] leading-[1.08] text-[#f2efe8]">
                        Sudah siap membuka pintu?
                    </h2>
                    <p class="mt-3 text-[13.5px] leading-relaxed text-neutral-400 max-w-3xl">
                        Semua tahapan onboarding sudah membimbing langkah demi langkah. Klik centang setiap item yang sudah benar-benar selesai. Progress secara otomatis disimpan di browser kamu.
                    </p>
                </header>

                <ul class="grid md:grid-cols-2 gap-x-8 gap-y-4">
                    @foreach($checklist as $i => $c)
                    <li>
                        <label class="flex items-start gap-4 p-4 rounded-xl border border-neutral-800 hover:border-neutral-700 hover:bg-white/[0.015] cursor-pointer transition-all select-none" @click="toggle({{ $i }})">
                            <span class="mt-0.5 h-5 w-5 shrink-0 rounded border-2 flex items-center justify-center transition-all"
                                :class="isChecked({{ $i }}) ? 'border-[#c7a64a] bg-[#c7a64a]' : 'border-neutral-700 bg-transparent'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-black" :class="isChecked({{ $i }}) ? 'block' : 'hidden'" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="flex flex-col gap-1 min-w-0">
                                <span class="text-[13.5px] font-semibold text-[#f2efe8]">{{ $c['text'] }}</span>
                                <span class="text-[11.5px] text-neutral-500 leading-relaxed">{{ $c['sub'] }}</span>
                            </span>
                        </label>
                    </li>
                    @endforeach
                </ul>
            </section>

            <div id="faq" class="border-t border-b border-neutral-800 py-3.5 flex flex-wrap items-center justify-between gap-3 text-[10.5px] uppercase tracking-[0.22em] scroll-mt-40">
                <p class="text-neutral-500 font-semibold">FAQ · PENULIS PEMULA</p>
                <p class="text-neutral-600">Pertanyaan yang sering diajukan penulis baru sebelum menerbitkan novel pertama di Chapterly.</p>
                <p class="text-neutral-500 font-semibold">DISKUSI GRUP →</p>
            </div>

            <section class="space-y-7" x-data="{ open: 0 }">
                <header class="border-l-2 border-[#c7a64a]/60 pl-5 md:pl-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#c7a64a] mb-2">HAL YANG MUNGKIN KAMU TANYAKAN</p>
                    <h2 class="font-serif text-[32px] md:text-[40px] leading-[1.08] text-[#f2efe8]">
                        Hal yang mungkin kamu tanyakan.
                    </h2>
                </header>

                <div class="space-y-3">
                    @foreach($faqs as $i => $f)
                    <div class="border border-neutral-800 rounded-xl overflow-hidden bg-[#0a0a0a]/60 hover:bg-white/[0.015] transition-colors">
                        <button type="button" @click="open = (open === {{ $i }}) ? -1 : {{ $i }}" class="w-full flex items-center justify-between gap-4 px-5 md:px-6 py-4 md:py-5 text-left">
                            <span class="flex items-center gap-4 min-w-0">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-[#c7a64a]/30 text-[10px] font-bold text-[#c7a64a] tabular-nums tracking-wider">{{ sprintf('%02d', $i+1) }}</span>
                                <span class="text-[14.5px] md:text-[15px] font-semibold text-[#f2efe8] leading-snug">{{ $f['q'] }}</span>
                            </span>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-neutral-800 text-neutral-500 transition-all"
                                :class="open === {{ $i }} ? 'text-[#c7a64a] border-[#c7a64a]/40 bg-[#c7a64a]/10 rotate-180' : ''">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                        <div class="overflow-hidden"
                             x-show="open === {{ $i }}"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="max-h-0 opacity-0"
                             x-transition:enter-end="max-h-[500px] opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="max-h-[500px] opacity-100"
                             x-transition:leave-end="max-h-0 opacity-0"
                             style="display: none;"
                        >
                            <div class="px-5 md:px-6 pb-5 md:pb-6 pl-[72px] md:pl-[88px]">
                                <div class="border-t border-neutral-800 pt-4 md:pt-5 text-[13.5px] md:text-[14px] leading-[1.9] text-neutral-400">
                                    {{ $f['a'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            <section class="relative overflow-hidden rounded-[28px] border border-[#c7a64a]/20 bg-gradient-to-br from-[#151108] via-[#0a0a0a] to-[#181410] p-8 md:p-12 lg:p-16">
                <div class="absolute -top-24 -right-16 w-[380px] h-[380px] rounded-full bg-[#c7a64a]/10 blur-[120px]"></div>
                <div class="relative grid md:grid-cols-[minmax(0,1.6fr)_auto] gap-10 items-end">
                    <div class="space-y-6 min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-[0.32em] text-[#c7a64a]">PENUTUP · CHAPTERLY STUDIO</p>
                        <h2 class="font-serif text-[38px] md:text-[50px] leading-[1.05] text-[#f2efe8]">
                            Mulai kecil. Tulis dengan sungguh-sungguh.
                        </h2>
                        <p class="text-[13.5px] md:text-base leading-relaxed text-neutral-400 max-w-xl">
                            Mulailah baris pertama. Tuliskan satu kalimat hari ini.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        @auth
                            @if(Auth::user()->role === 'writer')
                                <a href="{{ route('writer.dashboard') }}" class="inline-flex items-center gap-3 px-8 py-3.5 bg-[#c7a64a] hover:bg-[#d4b356] text-black text-[11.5px] font-bold uppercase tracking-[0.22em] rounded-md shadow-lg shadow-[#c7a64a]/20 active:scale-[0.98] transition-all">
                                    Buka dashboard →
                                </a>
                            @else
                                <form method="POST" action="{{ route('dashboard.become-writer') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-3 px-8 py-3.5 bg-[#c7a64a] hover:bg-[#d4b356] text-black text-[11.5px] font-bold uppercase tracking-[0.22em] rounded-md shadow-lg shadow-[#c7a64a]/20 active:scale-[0.98] transition-all">
                                        Mulai prosesmu →
                                    </button>
                                </form>
                            @endif
                        @endauth
                        @guest
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-8 py-3.5 bg-[#c7a64a] hover:bg-[#d4b356] text-black text-[11.5px] font-bold uppercase tracking-[0.22em] rounded-md shadow-lg shadow-[#c7a64a]/20 active:scale-[0.98] transition-all">
                                Mulai prosesmu →
                            </a>
                        @endguest
                        <a href="{{ route('guides.index') }}" class="px-5 py-3 border border-neutral-700 rounded-md text-[11px] font-semibold uppercase tracking-[0.22em] text-neutral-400 hover:text-white hover:border-neutral-600 hover:bg-white/5 transition-colors">
                            ← Kembali ke Guideline Center
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </section>

    @if(!$useWriterShell)
    <div class="mt-20 pt-10 border-t border-neutral-900 grid grid-cols-1 md:grid-cols-3 gap-6 text-[11px] text-neutral-600">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.26em] text-neutral-500 mb-3">CHAPTERLY</p>
            <p class="leading-relaxed">Studio penulisan oleh Quoros. Temukan cara menulis yang kamu sukai, lalu konsistenlah dengannya.</p>
        </div>
        <div class="md:col-span-2 md:justify-self-end md:text-right space-x-6 whitespace-nowrap">
            <a href="#" class="hover:text-[#c7a64a] transition-colors">Rekomendasi</a>
            <a href="#" class="hover:text-[#c7a64a] transition-colors">Kontributor</a>
            <a href="#" class="hover:text-[#c7a64a] transition-colors">Genre &amp; Topik</a>
            <a href="#" class="hover:text-[#c7a64a] transition-colors">Contact kami ↗</a>
        </div>
    </div>
    @endif
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #404040; border-radius: 10px; }
</style>
@endsection
