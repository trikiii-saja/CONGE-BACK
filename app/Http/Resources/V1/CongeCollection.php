<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CongeCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($conge) {
            return new CongeResource($conge);
        });
    }
}
