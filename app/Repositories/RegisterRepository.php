<?php

namespace App\Repositories;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterRepository
{
    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'password']);
        DB::beginTransaction();
        try {

            $user = User::create($data);

            DB::commit();

            return [
                'user' => $user->toArray(),
                'message' => "We have sent email to confirm your account.Please check your email"
            ];
        } catch (Throwable $e) {
            Log::error(__FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            DB::rollBack();
            abort(500, 'Account Registration is failed.');
        }
    }
}
