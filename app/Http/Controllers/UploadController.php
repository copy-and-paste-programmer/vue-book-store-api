<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function images(Request $request)
    {
        $this->imageService->upload($request->file('images'));
    }
}
