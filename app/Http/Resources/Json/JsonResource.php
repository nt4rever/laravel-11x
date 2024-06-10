<?php

namespace App\Http\Resources\Json;

use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->with = [
            // 'success' => true,
            // 'timestamp' => now()->toIso8601String(),
        ];
        parent::__construct($resource);
    }
}
