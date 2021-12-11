<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class RankResource extends JsonResource
{


    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'required_points' => $this->required_points,
            'created_at' => $this->created_at,
        ];
    }
}