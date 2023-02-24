<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthenticationService
{
    public function httpPost(Request $http, $uri, $payload): object
    {
        try {
            $http->request->add($payload);

            $tokenRequest = $http->create(uri: $uri, method: 'POST');

            $response = Route::dispatch($tokenRequest);

            if ($response->getStatusCode() !== 200) {
                abort($response->getStatusCode(), $response->getContent());
            }

            return json_decode($response->getContent());
        } catch (HttpException $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            abort($e->getStatusCode(), 'Request is failed');
        }
    }
}
