<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_KEY'),
                'api_secret' => env('CLOUDINARY_SECRET'),
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
            'public_id' => $result['public_id'],
        ];
    }

    public function deleteImage(string $publicId): void
    {
        $this->cloudinary->uploadApi()->destroy($publicId);
    }
}
