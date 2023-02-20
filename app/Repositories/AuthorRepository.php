<?php

namespace App\Repositories;

use App\Models\Author;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AuthorResource;

class AuthorRepository
{
    public function getAuthor() {
        $author = Author::all();
        return AuthorResource::collection($author);
    }

    public function createAuthor($data) {
        $author = new Author;
        $author->name = $data->name;
        $author->email = $data->email;
        $author->description = $data->description;
        $author->created_at = now();
        $author->save();
        return true;
    }

    public function updateAuthor($data) {
        $author = new Author;
        $author->name = $data->name;
        $author->email = $data->email;
        $author->description = $data->description;
        $author->updated_at = now();
        $author->update();
        return true;
    }
}
