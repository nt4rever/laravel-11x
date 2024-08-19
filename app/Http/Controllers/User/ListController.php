<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexRequest;
use App\Http\Resources\UserCollection;

class ListController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        return new UserCollection(
            $request->buildQueryBuilder()
                ->paginate($request->getLimit())
        );
    }
}
