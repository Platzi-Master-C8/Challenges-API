<?php

namespace App\Http\Resources\V1;

use App\Models\Rank;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RankCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->getRanks(),
            'meta' => [
                'api-version' => 'V1',
            ],
        ];
    }

    public function getRanks()
    {
        return Rank::all()->map(function ($rank) {
            return [
                'id' => $rank->id,
                'name' => $rank->name,
                'required_points' => $rank->required_points,
            ];
        });

    }
}
