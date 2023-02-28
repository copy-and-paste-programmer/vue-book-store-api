<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function upload(UploadedFile $file, $folder = null)
    {
        $path = 'upload/' . $folder ;

        $uploadPath = Storage::put($path, $file);

        return new Image([
            'disk' => config('filesystems.default'),
            'path' => $uploadPath,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'url' => Storage::url($uploadPath),
        ]);
    }
}
