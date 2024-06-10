<?php

namespace App\Http\Resources;

use App\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
