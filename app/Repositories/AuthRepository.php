<?php

namespace App\Repositories;

use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthRepository
{
    public function authenticated(Request $request)
    {
        $token = $this->getToken($request);
        $token->user = User::with(['image'])->where('email', $request->email)->first();
        return response()->json($token, 200);
    }

    private function getToken(Request $request)
    {
        try {
            $client = Client::where('id', 2)->first();

            $request->request->add([
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ]);

            $tokenRequest = $request->create('http://localhost:8000/oauth/token', method:'POST');

            $response = Route::dispatch($tokenRequest);

            if ($response->getStatusCode() !== 200) {
                abort($response->getStatusCode(), $response->getContent());
            }

            return json_decode($response->getContent());

        } catch (HttpException $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            abort($e->getStatusCode(), 'Login Failed');
        }
    }
}
