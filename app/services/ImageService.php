<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function upload(UploadedFile $file, $prefix = null)
    {
        $path = 'upload/' . $prefix ;

        $uploadPath = Storage::put($path, $file);

        return new Image([
            'disk' => config('filesystems.default'),
            'path' => $uploadPath,
            'url'  => Storage::url($uploadPath),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }
}
