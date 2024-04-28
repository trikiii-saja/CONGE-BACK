<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CongeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start' => $this->start,
            'end' => $this->end,
            'reason' => $this->reason,
            'status' => $this->status,
            'user' => new UserResource($this->user),
        ];
    }
}
