<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function authenticated(Request $request)
    {
        $user = User::with(['image'])->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            abort(401, 'Your email or password is wrong.');
        }

        $token = $user->createToken($request->header('User-Agent'))->accessToken;

        return [
            'user' => $user->toArray(),
            'access_token' => $token,
        ];
    }
}
