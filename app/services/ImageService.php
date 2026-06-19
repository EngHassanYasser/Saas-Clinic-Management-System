<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public static function upload(UploadedFile $image, string $folder = 'images'): string
    {
        $path = $image->store($folder, 'public');

        return basename($path);
    }

    public static function delete(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    public static function exists(string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }

    public static function getUrl(string $path): string
    {
        return Storage::url($path);
    }

    public static function update(
        UploadedFile $image,
        ?string $oldPath,
        string $folder = 'images'
    ): string {
        if ($oldPath && self::exists($oldPath)) {
            self::delete($oldPath);
        }

        $path = self::upload($image, $folder);

        return basename($path);
    }
}
