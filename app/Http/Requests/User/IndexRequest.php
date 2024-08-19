<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AbstractIndexRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class IndexRequest extends AbstractIndexRequest
{
    protected function getModel(): Model
    {
        return new User();
    }
}
