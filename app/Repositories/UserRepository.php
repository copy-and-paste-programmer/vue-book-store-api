<?php

namespace App\Repositories;

use Throwable;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    /**
     * Add books to favorite list
     */
    public function addToFavorite($user_id,$book_id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($user_id);
            if($user->books()->where('book_id', $book_id)->exists()){
                $user->books()->detach($book_id);
            } else $user->books()->attach($book_id);
            DB::commit();
        }
        catch(Throwable $e){
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            db::rollBack();
            abort(500 , "Can't add to favorite list!");
        }
    }

    /**
     * get favorite book list
     */
    public function favoriteBook($user_id)
    {
        return User::with('books')->where('id' , $user_id)->get();
    } 
}