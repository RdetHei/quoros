<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_KEY');
        $apiSecret = env('CLOUDINARY_SECRET');

        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            throw new \RuntimeException('Cloudinary credentials are not configured. Please set CLOUDINARY_CLOUD_NAME, CLOUDINARY_KEY, and CLOUDINARY_SECRET in the .env file.');
        }

        $this->cloudinary = new Cloudinary([
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
            $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => $folder,
            ]);

            $url = $this->cloudinary->image($result['public_id'])
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

    public function deleteImage(string $publicId): void
    {
        if (empty($publicId)) {
            return;
        }

        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
        } catch (\Throwable $e) {
            Log::warning('Cloudinary destroy failed', [
                'public_id' => $publicId,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
