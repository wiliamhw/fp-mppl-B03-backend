<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function connection(): JsonResponse
    {
        return new JsonResponse([
            'data' => 'Mantap!! Apknya sudah tersambung dengan backend, hehe :)'
        ]);
    }

    public function login(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
