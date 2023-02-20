<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthorRequest;
use App\Repositories\AuthorRepository;

class AuthorController extends Controller
{
    public $authorRepository;

    public function __construct(AuthorRepository $authorRepository) {
        $this->authorRepository = $authorRepository;
    }

    public function getAuthor(){
        $authors =  $this->authorRepository->getAuthor();
        return response()->json($authors);
    }

    public function create(AuthorRequest $request){
        $success = $this->authorRepository->createAuthor($request);
        if($success) {
            return response()->json(['message' => "Author create successfully."]);
        }
        return response()->json(['code'=>404,'message'=>"Can't create author."], 404);
    }

    // public function update(AuthorUpdateRequest $request){
        
    // }
}
