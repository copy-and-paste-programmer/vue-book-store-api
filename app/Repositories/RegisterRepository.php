<?php

namespace App\Repositories;

use Throwable;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterRepository
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'password']);

        DB::beginTransaction();

        try {

            $user = User::create($data);

            if ($request->hasFile('image')) {
                $image = $this->imageService->upload($request->file('image'), 'user');
                $user->image()->save($image);
            }

            DB::commit();

        } catch (Throwable $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            DB::rollBack();
            abort(500, 'Account Registration is failed.');
        }
    }
}
