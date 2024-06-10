<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $inputs = $request->validated();
        if (!auth()->attempt($inputs)) {
            abort(Response::HTTP_UNAUTHORIZED, __('auth.failed'));
        }

        /** @var User $user */
        $user = auth()->user();
        $token = $user->createToken(name: 'api', expiresAt: now()->addMinutes(config('sanctum.expiration')));

        event(new UserLoggedIn($user));

        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
            'user' => new UserResource($user),
        ]);
    }
}
