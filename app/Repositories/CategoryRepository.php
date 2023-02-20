<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryRepository 
{
    /**
     * Get Build Types
     *
     * @return array
     */
    public function index()
    {
        $categories = Category::all();

        return CategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        return Category::create([
            'name' => $request->name
        ]);
    }

    public function update(Request $request ,$id)
    {   
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name
        ]);

        return $category;
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'A category delete successfully'
        ], 200);
    }
}
