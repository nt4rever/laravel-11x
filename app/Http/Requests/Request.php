<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * Convert to boolean
     *
     * @param $bool
     * @return bool|null
     */
    public function toBoolean($bool): bool|null
    {
        return filter_var($bool, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
