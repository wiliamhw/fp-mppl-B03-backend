<?php

namespace App\Http\Controllers;

use App\Exceptions\Concerns\HandleApiExceptions;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use HandleApiExceptions;
    use ApiResponse;

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                $message = (!$user) ? 'The email is incorrect' : 'The password is incorrect';
                throw ValidationException::withMessages([
                    'message' => [$message],
                ]);
            }

            $token = $user->createToken('ApiToken')->plainTextToken;
            return $this->sendData([
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return $this->renderApiException($e);
        }
    }
}
