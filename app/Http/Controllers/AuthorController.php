<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthorRequest;
use App\Repositories\AuthorRepository;
use App\Http\Requests\AuthorUpdateRequest;
use App\Services\ImageService;

class AuthorController extends Controller
{
    public $authorRepository;

    public $imageService;

    public function __construct(AuthorRepository $authorRepository , ImageService $imageService) {
        $this->authorRepository = $authorRepository;
        $this->imageService = $imageService;
    }

    public function getAuthor(){
        $authors =  $this->authorRepository->getAuthor();
        return response()->json($authors);
    }

    public function create(AuthorRequest $request) {
        $image = $this->imageService->upload($request->file('image'));
        $success = $this->authorRepository->createAuthor($request , $image);
        if($success) {
            return response()->json(['message' => "Author create successfully."]);
        }
        return response()->json(['code'=>404,'message'=>"Can't create author."], 404);
    }

    public function update(AuthorUpdateRequest $request){
        $success = $this->authorRepository->updateAuthor($request);
        // if($success){

        // }
    }
}
