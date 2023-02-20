<?php

namespace App\Repositories;

use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Repositories\Repository;

class CategoryRepository extends Repository
{
    public function getAuthor() {
        $author = Author::get();
        return AuthorResource::collection($author);
    }
}
