<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Http\Requests\AuthorUpdateRequest;
use App\Http\Resources\AuthorResource;
use App\Repositories\AuthorRepository;
use App\Services\ImageService;

class AuthorController extends Controller
{
    private $authorRepository;

    private $imageService;

    public function __construct(AuthorRepository $authorRepository, ImageService $imageService)
    {
        $this->authorRepository = $authorRepository;
        $this->imageService = $imageService;
    }

    /**
     * get author
     */
    public function index()
    {
        $authors = $this->authorRepository->index();
        // return response()->json($authors);
        return AuthorResource::collection($authors);
    }

    /**
     * create author
     */
    public function create(AuthorRequest $request)
    {
        $image = $this->imageService->upload($request->file('image'));
        $success = $this->authorRepository->create($request, $image);
        if ($success) {
            return response()->json(['message' => "Author create successfully."]);
        }
        return response()->json(['code' => 404, 'message' => "Can't create author."], 404);
    }

    /**
     * update author
     */
    public function update(AuthorUpdateRequest $request, $id)
    {
        $image = $this->imageService->upload($request->file('image'));
        $success = $this->authorRepository->update($request, $image, $id);
        if ($success) {
            return response()->json(['message' => "Author update successfully."]);
        }
        return response()->json(['code' => 404, 'message' => "Can't update author."]);
    }

    /**
     * delete author
     */
    public function destroy($id)
    {
        $success = $this->authorRepository->destroy($id);
        if ($success) {
            return response()->json(['message' => 'Author delete successfully.']);
        }
        return response()->json(['message' => "Can't delete author."]);
    }
}
