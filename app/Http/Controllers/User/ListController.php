<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;

class ListController extends Controller
{
    public function __invoke()
    {
        return new UserCollection(User::paginate(100));
    }
}
