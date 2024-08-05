<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Resources\UserResource;
use App\Models\User;

class CreateController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::create($request->only(['name', 'email', 'password']));

        return new UserResource($user);
    }
}
