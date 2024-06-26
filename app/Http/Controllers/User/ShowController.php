<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Cache;

class ShowController extends Controller
{
    public function __invoke(User $user)
    {

        return new UserResource($user);
    }
}
