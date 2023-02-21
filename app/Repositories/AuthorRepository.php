<?php

namespace App\Repositories;

use App\Models\Author;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AuthorResource;
use Illuminate\Database\Events\TransactionRolledBack;
use Throwable;

class AuthorRepository
{
    /**
     * get author
     */
    public function getAuthor() {
        $author = Author::all();
        return AuthorResource::collection($author);
    }

    /**
     * create author
     */
    public function createAuthor($data,$image) {
        DB::beginTransaction();
        try {
            $author = new Author;
            $author->name = $data->name;
            $author->email = $data->email;
            $author->description = $data->description;
            $author->created_at = now();
            $author->save();
            $author->image()->save($image);
            DB::commit();
            return true;
        }
        catch(\Throwable $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * update author
     */
    public function updateAuthor($data , $image , $id) {
        DB::beginTransaction();
        try {
            $author = Author::where('id',$id)->first();
            $author->name = $data->name;
            $author->email = $data->email;
            $author->description = $data->description;
            $author->updated_at = now();
            $author->update();
            $oldimage = $author->image;
            if($oldimage){
                $oldimage->delete();
            }
            $author->image()->save($image);
            DB::commit();
            return true;
        }
        catch(Throwable $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * delete author
     */
    public function deleteAuthor($id)
    {
        $author = Author::find($id);
        $author->image()->delete();
        $author->delete();
        return true;
    }
}
