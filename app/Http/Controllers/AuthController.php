<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function authenticated(Request $request)
    {
        $data = $this->authRepository->authenticated($request);

        return response()->json($data, 200);
    }
}
