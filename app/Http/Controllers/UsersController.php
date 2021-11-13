<?php

namespace App\Http\Controllers;

use App\Exceptions\Concerns\HandleApiExceptions;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserSaveRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class UsersController extends Controller
{
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
            ], 'The user has been logged in.');
        } catch (\Exception $e) {
            return $this->renderApiException($e);
        }
    }

    /**
     * Show Resource.
     * Display a specific user resource identified by the given id/key.
     *
     * @authenticated
     *
     * @param Request $request
     * @return UserResource
     */
    public function show(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * Create Resource.
     * Create a new user resource.
     *
     * @param \App\Http\Requests\UserSaveRequest $request
     * @param \App\Models\User $user
     *
     * @return JsonResponse
     */
    public function store(UserSaveRequest $request, User $user): JsonResponse
    {
        $userData = $request->only($user->offsetGet('fillable'));
        $userData['password'] = Hash::make($userData['password']);
        $user->fill($userData)->save();

        $resource = (new UserResource($user))
            ->additional([
                'info'  => 'The new user has been saved.',
                'token' => $user->createToken('ApiToken')->plainTextToken
            ]);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Update Resource.
     * Update a specific user resource identified by the given id/key.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\UserSaveRequest $request
     *
     * @return UserResource
     */
    public function update(UserSaveRequest $request): UserResource
    {
        $user = $request->user();
        $user->fill($request->only($user->offsetGet('fillable')));

        if ($user->isDirty()) {
            $user->save();
        }

        return (new UserResource($user))
            ->additional(['info' => 'The user has been updated.']);
    }

    /**
     * Revoke Sanctum token
     *
     * @authenticated
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();
            return $this->sendOk('The user has been logged out.');
        } catch (\Exception $e) {
            return $this->renderApiException($e);
        }
    }
}
