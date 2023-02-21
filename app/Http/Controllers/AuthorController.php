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

    /**
     * get author
     */
    public function getAuthor()
    {
        $authors =  $this->authorRepository->getAuthor();
        return response()->json($authors);
    }

    /**
     * create author
     */
    public function create(AuthorRequest $request) 
    {
        $image = $this->imageService->upload($request->file('image'));
        $success = $this->authorRepository->createAuthor($request , $image);
        if($success) {
            return response()->json(['message' => "Author create successfully."]);
        }
        return response()->json(['code'=>404,'message'=>"Can't create author."], 404);
    }

    /**
     * update author
     */
    public function update(AuthorUpdateRequest $request , $id)
    {
        $image = $this->imageService->upload($request->file('image'));
        $success = $this->authorRepository->updateAuthor($request,$image,$id);
        if($success){
            return response()->json(['message' => "Author update successfully."]);
        }
        return response()->json(['code' => 404,'message'=>"Can't update author."]);
    }

    /**
     * delete author
     */
    public function delete($id) {
        $success = $this->authorRepository->deleteAuthor($id);
        if($success){
            return response()->json(['message'=>'Author delete successfully.']);
        }
        return response()->json(['message'=>"Can't delete author."]);
    }
}
