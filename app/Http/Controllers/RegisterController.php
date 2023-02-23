<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\RegisterRepository;

class RegisterController extends Controller
{
    protected $registerRepository;

    public function __construct(RegisterRepository $registerRepository)
    {
        $this->registerRepository = $registerRepository;
    }

    public function register(Request $request)
    {
        $this->registerRepository->register($request);
        return response()->json(['message' => 'Register Successfully'], 200);
    }
}
