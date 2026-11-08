<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CloudinaryService
{
    protected ?Cloudinary $cloudinary = null;

    /**
     * Bangun client Cloudinary hanya saat dibutuhkan. Sengaja lazy supaya
     * halaman yang tidak mengunggah apa pun (mis. beranda) tetap dapat
     * diakses meskipun kredensial Cloudinary belum dikonfigurasi.
     */
    protected function cloudinary(): Cloudinary
    {
        if ($this->cloudinary instanceof Cloudinary) {
            return $this->cloudinary;
        }

        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_KEY');
        $apiSecret = env('CLOUDINARY_SECRET');

        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            throw new \RuntimeException('Cloudinary credentials are not configured. Please set CLOUDINARY_CLOUD_NAME, CLOUDINARY_KEY, and CLOUDINARY_SECRET in the .env file.');
        }

        return $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
        ]);
    }

    public function uploadCover(UploadedFile $file): array
    {
        // Karena user sudah melakukan cropping di frontend, kita cukup pastikan ukurannya pas
        return $this->upload($file, 'covers', Resize::fill(600, 800));
    }

    public function uploadCharacter(UploadedFile $file): array
    {
        return $this->upload($file, 'characters', Resize::fill(600, 600));
    }

    public function uploadProfile(UploadedFile $file): array
    {
        return $this->upload($file, 'profiles', Resize::fill(300, 300));
    }

    protected function upload(UploadedFile $file, string $folder, $resizeAction): array
    {
        try {
            $result = $this->cloudinary()->uploadApi()->upload($file->getRealPath(), [
                'folder' => $folder,
            ]);

            $url = $this->cloudinary()->image($result['public_id'])
                ->resize($resizeAction)
                ->format('auto')
                ->quality('auto')
                ->toUrl();

            return [
                'url' => $url,
                'public_id' => $result['public_id'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Cloudinary upload failed', [
                'message' => $e->getMessage(),
                'folder' => $folder,
                'file' => $file->getClientOriginalName(),
            ]);

            throw new \RuntimeException('Upload gagal ke Cloudinary. Periksa kredensial Cloudinary dan koneksi API.', 0, $e);
        }
    }

    /**
     * Upload file chapter (PDF/EPUB/DOCX) sebagai resource "raw".
     * Diperlukan di Vercel karena filesystem container tidak persisten.
     */
    public function uploadChapterFile(UploadedFile $file): array
    {
        try {
            $result = $this->cloudinary()->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'chapters',
                'resource_type' => 'raw',
                'use_filename' => true,
                'unique_filename' => true,
            ]);

            return [
                'url' => $result['secure_url'] ?? $result['url'],
                'public_id' => $result['public_id'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Cloudinary raw upload failed', [
                'message' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);

            throw new \RuntimeException('Upload file chapter gagal ke Cloudinary. Periksa kredensial Cloudinary dan koneksi API.', 0, $e);
        }
    }

    /**
     * Hapus file chapter. Menerima URL Cloudinary penuh maupun path lokal
     * lama (mis. "chapters/foo.pdf") supaya data sebelum migrasi tetap bersih.
     */
    public function deleteChapterFile(?string $stored): void
    {
        if (empty($stored)) {
            return;
        }

        $publicId = $this->publicIdFromUrl($stored);

        if ($publicId === null) {
            Storage::disk('public')->delete($stored);

            return;
        }

        try {
            $this->cloudinary()->uploadApi()->destroy($publicId, ['resource_type' => 'raw']);
        } catch (\Throwable $e) {
            Log::warning('Cloudinary raw destroy failed', [
                'public_id' => $publicId,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Ambil public_id dari URL Cloudinary. Null jika bukan URL Cloudinary.
     */
    protected function publicIdFromUrl(string $url): ?string
    {
        if (! str_contains($url, 'res.cloudinary.com')) {
            return null;
        }

        if (preg_match('#/(?:raw|image|video)/upload/(?:v\d+/)?(.+)$#', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function deleteImage(string $publicId): void
    {
        if (empty($publicId)) {
            return;
        }

        try {
            $this->cloudinary()->uploadApi()->destroy($publicId);
        } catch (\Throwable $e) {
            Log::warning('Cloudinary destroy failed', [
                'public_id' => $publicId,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
