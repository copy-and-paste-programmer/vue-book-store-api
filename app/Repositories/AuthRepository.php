<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AuthRepository
{
    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'password']);
        DB::beginTransaction();
        try {

            $user = User::create($data);
            $token = $user->createToken('Book-Store-Api')->accessToken;

            DB::commit();

            return [
                'user' => $user->toArray(),
                'access_token' => $token
            ];
        } catch (Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }
}
