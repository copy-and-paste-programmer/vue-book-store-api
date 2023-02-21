<?php

namespace App\Repositories;

use Throwable;
use App\Models\Author;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AuthorResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Events\TransactionRolledBack;

class AuthorRepository
{
    /**
     * get author
     */
    public function index() {
        $author = Author::paginate(10);
        return AuthorResource::collection($author);
    }

    /**
     * create author
     */
    public function create($data,$image) {
        DB::beginTransaction();
        try {
            $author = Author::create([
                'name' => $data->name,
                'email' => $data->email,
                'description' => $data->description,
            ]);
            $author->image()->save($image);  
            DB::commit();
            return true;
        }
        catch(\Throwable $e) {
            DB::rollback();
            abort(500);
        }
    }

    /**
     * update author
     */
    public function update($data , $image , $id) 
    {
        $author = Author::findOrFail($id);
        DB::beginTransaction();
        try {
            $author->update([
                'name' => $data->name,
                'email' => $data->email,
                'description' => $data->description,
            ]);
            
            $oldimage = $author->image;
            if($oldimage){
                $oldimage->delete();
                Storage::delete($oldimage->path);
            }
            $author->image()->save($image);
            DB::commit();
            return true;
        }
        catch(Throwable $e) {
            DB::rollback();
            abort(500);
        }
    }

    /**
     * delete author
     */
    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        $author->image()->delete();
        $author->delete();
        return true;
    }
}
