<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Resources\UserResource;

class MeController extends Controller
{
    public function __invoke(Request $request)
    {
        return new UserResource($request->user());
    }
}
