<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return $this->categoryRepository->index();
    }

    public function store(CategoryRequest $request)
    {
        return $this->categoryRepository->store($request);

    }

    public function update(CategoryRequest $request, $id)
    {
        return $this->categoryRepository->update($request, $id);
    }

    public function destroy($id)
    {
        $this->categoryRepository->destroy($id);
        
        return response()->json([
            'message' => 'The category delete successfully'
        ], 200);
    }

}
