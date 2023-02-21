<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Repositories\AuthorRepository;
use App\Http\Requests\AuthorUpdateRequest;
use App\Http\Resources\AuthorResource;

class AuthorController extends Controller
{
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * get author
     */
    public function index()
    {
        $authors =  $this->authorRepository->index();
        return AuthorResource::collection($authors);
    }

    /**
     * create author
     */
    public function create(AuthorRequest $request)
    {
        $author = $this->authorRepository->create($request);
        return new AuthorResource($author);
    }

    /**
     * update author
     */
    public function update(AuthorUpdateRequest $request, $id)
    {
        $author = $this->authorRepository->update($request, $id);
        return new AuthorResource($author);
    }

    /**
     * delete author
     */
    public function destroy($id)
    {
        $this->authorRepository->destroy($id);
        return response()->json(['message' => 'The author is deleted.'], 200);
    }
}
