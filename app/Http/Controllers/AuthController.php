<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Repositories\AuthRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Throwable;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return object
     */
    public function authenticated(Request $request): object
    {
        $data = $this->authRepository->authenticated($request);

        return response()->json($data, 200)->withCookie(cookie(
            name: 'refresh_token',
            value: 'Bearer' . ' ' . $data->refresh_token,
            secure: true,
            minutes: 30 * 24 * 60,
        ));
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return object
     */
    public function refresh(Request $request): object
    {
        $data = $this->authRepository->refresh($request);

        return response()->json($data, 200)->withCookie(cookie(
            name: 'refresh_token',
            value: 'Bearer' . ' ' . $data->refresh_token,
            secure: true,
            minutes: 30 * 24 * 60,
        ));;
    }
}
