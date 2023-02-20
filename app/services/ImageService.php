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
        $extension = $file->getClientOriginalExtension();

        $fileName =  Str::random(20) . uniqid() . '.'. $extension;

        $path = 'upload/' . $prefix . '/' . $fileName;

        Storage::put($path, $file);

        return new Image([
            'disk' => config('filesystems.default'),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }
}
