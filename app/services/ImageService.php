<?php

namespace App\services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function upload(UploadedFile $image, string $folder = 'images'): string
    {
        $path = $image->store($folder, 'public');

        return basename($path);
    }

    public function delete(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    public function exists(string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }

    public function getUrl(string $path): string
    {
        return Storage::url($path);
    }

    public function update(
        UploadedFile $image,
        ?string $oldPath,
        string $folder = 'images'
    ): string {
        if ($oldPath && $this->exists($oldPath)) {
            $this->delete($oldPath);
        }

        $path = $this->upload($image, $folder);
        return basename($path);
    }
}
