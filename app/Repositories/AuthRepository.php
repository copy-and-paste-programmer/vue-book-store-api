<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Laravel\Passport\Client;


class AuthRepository
{
    private $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function authenticated(Request $request) :object
    {
        $client = Client::where('id', 2)->first();

        $payload = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*'
        ];

        $data = $this->authenticationService->httpPost(
            http: $request,
            uri: 'http://localhost:8000/oauth/token',
            payload: $payload
        );

        $data->user = User::with(['image'])->where('email', $request->email)->first();

        return $data;
    }

    public function refresh(Request $request) : object
    {
        $client = Client::where('id', 2)->first();
        $refreshToken = $request->cookie('refresh_token') ?? $request->bearerToken();

        $payload = [
            'grant_type' => 'refresh_token',
            'refresh_token' =>  $refreshToken,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => '*',
        ];

        $data = $this->authenticationService->httpPost(
            http: $request,
            uri: 'http://localhost:8000/oauth/token',
            payload: $payload
        );

        $data->user = User::with(['image'])->where('email', $request->email)->first();

        return $data;
    }
}
