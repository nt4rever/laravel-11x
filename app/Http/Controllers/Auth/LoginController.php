<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $request->authenticate();

        /**
         * @var \App\Models\User
         */
        $user = $request->user();

        $token = $user->createToken(name: 'api', expiresAt: now()
            ->addMinutes(config('sanctum.expiration')));

        event(new UserLoggedIn($user));

        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
            'user' => new UserResource($user),
        ]);
    }
}
