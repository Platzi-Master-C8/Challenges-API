<?php

namespace App\Http\Resources\V1;

use App\Models\Challenge;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChallengeChallengerCollection extends ResourceCollection
{
    public $collects = Challenge::class;

    public function toArray($request): array
    {
        return $this->getChallenges();
    }

    private function getChallenges(): array
    {
        return $this->map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'description' => $item['description'],
            ];
        })->toArray();
    }
}
