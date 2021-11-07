<?php

namespace App\Http\Controllers;

use App\Exceptions\Concerns\HandleApiExceptions;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Request;

class UserLoginController extends Controller
{
    use HandleApiExceptions;
    use ApiResponse;

    /**
     * Issue Sanctum token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
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

            return $this->sendData([
                'token' => $user->createToken('ApiToken')->plainTextToken
            ]);
        } catch (\Exception $e) {
            return $this->renderApiException($e);
        }
    }

    /**
     * Issue Sanctum token
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->sendData([
                'message' => 'Token Revoked',
                'code'  => 200
            ]);
        } catch (\Exception $e) {
            return $this->renderApiException($e);
        }
    }
}
