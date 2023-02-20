<?php

namespace App\Repositories;

use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryRepository 
{
    /**
     * Get Build Types
     *
     * @return array
     */
    public function getAll()
    {
        $categories = Category::all();

        return CategoryResource::collection($categories);
    }
}
