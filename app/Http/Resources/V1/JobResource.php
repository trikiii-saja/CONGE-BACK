<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'company_id' => $this->company_id,
            'company' => new CompanyResource($this->Company),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
