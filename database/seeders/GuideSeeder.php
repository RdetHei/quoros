<?php

namespace Database\Seeders;

use App\Models\GuideArticle;
use App\Models\GuideCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GuideSeeder extends Seeder
{
    public function run(): void
    {
        // Category: Untuk Penulis
        $writerCategory = GuideCategory::updateOrCreate(
            ['slug' => 'untuk-penulis'],
            [
                'name' => 'Untuk Penulis',
                'description' => 'Pelajari cara mulai menulis, mengelola novel, dan membangun basis pembaca di Quoros.',
                'order' => 1,
            ]
        );

        GuideArticle::updateOrCreate(
            ['slug' => 'cara-menjadi-penulis-di-quoros'],
            [
                'guide_category_id' => $writerCategory->id,
                'title' => 'Cara Menjadi Penulis di Quoros',
                'content' => '
                    <h2>Langkah-langkah Menjadi Penulis</h2>
                    <p>Menjadi penulis di Quoros sangatlah mudah. Ikuti langkah-langkah berikut untuk mulai menerbitkan karya Anda:</p>
                    <ol>
                        <li><strong>Daftar Akun:</strong> Pastikan Anda sudah memiliki akun Quoros yang aktif.</li>
                        <li><strong>Buka Profil:</strong> Klik pada foto profil Anda di pojok kanan atas.</li>
                        <li><strong>Klik "Mulai Menulis":</strong> Temukan tombol berwarna hijau untuk mendaftar sebagai penulis.</li>
                        <li><strong>Akses Dashboard:</strong> Setelah berhasil, menu "Dashboard" akan muncul di profil Anda.</li>
                        <li><strong>Mulai Menulis:</strong> Masuk ke Dashboard dan klik "Buat Novel Baru" untuk mulai mengunggah karya pertama Anda.</li>
                    </ol>
                    <p><img src="https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&q=80&w=800" alt="Writing Image"></p>
                    <h3>Tips untuk Penulis Baru</h3>
                    <ul>
                        <li>Gunakan sampul novel yang menarik.</li>
                        <li>Tulis sinopsis yang membuat pembaca penasaran.</li>
                        <li>Update bab secara konsisten untuk menjaga engagement pembaca.</li>
                    </ul>
                ',
                'order' => 1,
                'is_published' => true,
            ]
        );

        // Category: Fitur Platform
        $featureCategory = GuideCategory::updateOrCreate(
            ['slug' => 'fitur-platform'],
            [
                'name' => 'Fitur Platform',
                'description' => 'Guide teknis mengenai penggunaan berbagai fitur canggih yang tersedia di platform Quoros.',
                'order' => 2,
            ]
        );

        GuideArticle::updateOrCreate(
            ['slug' => 'cara-menggunakan-fitur-bulk-upload'],
            [
                'guide_category_id' => $featureCategory->id,
                'title' => 'Cara Menggunakan Fitur Bulk Upload',
                'content' => '
                    <h2>Apa itu Bulk Upload?</h2>
                    <p>Fitur Bulk Upload memungkinkan Anda untuk mengunggah banyak bab sekaligus menggunakan file dokumen (DOCX, EPUB, PDF). Ini sangat menghemat waktu jika Anda sudah memiliki naskah yang lengkap.</p>
                    <p><img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=800" alt="Data Upload"></p>
                    <h3>Cara Menggunakannya:</h3>
                    <ol>
                        <li>Buka menu <strong>Novel Saya</strong> di Dashboard Writer.</li>
                        <li>Pilih novel yang ingin Anda tambahkan babnya.</li>
                        <li>Klik tombol <strong>Bulk Upload</strong>.</li>
                        <li>Pilih file dokumen Anda.</li>
                        <li>Sistem akan memproses dan memecah dokumen menjadi beberapa bab secara otomatis berdasarkan judul atau pemisah bab.</li>
                        <li>Tinjau kembali hasil konversi sebelum menerbitkannya.</li>
                    </ol>
                    <div class="bg-blue-900/20 p-6 rounded-2xl border border-blue-500/30 my-8">
                        <p class="font-bold text-blue-400 mb-2">Penting!</p>
                        <p class="text-sm">Pastikan format judul bab di dokumen Anda konsisten agar sistem dapat mendeteksi pemisah bab dengan akurat.</p>
                    </div>
                ',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Placeholder video
                'order' => 1,
                'is_published' => true,
            ]
        );
    }
}
