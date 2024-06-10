<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Resources\UserCollection;
use App\Models\User;

class ListController extends Controller
{
    public function __invoke(Request $request)
    {
        return new UserCollection(User::query()->paginate(15));
    }
}
