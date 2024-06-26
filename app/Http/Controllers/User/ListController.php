<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Cache;

class ListController extends Controller
{
    public function __invoke(Request $request)
    {
        $page = $request->integer('page', 1);
        $users = Cache::tags(['users'])->remember("users_p_{$page}", 300, function () use ($page) {
            return User::query()->paginate(perPage: 15, page: $page);
        });

        return new UserCollection($users);
    }
}
