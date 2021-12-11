<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;
use App\Http\Resources\V1\ChallengeResource;
use App\Http\Resources\V1\ChallengeCollection;

class ChallengeController extends Controller
{
    public function index()
    {
        return new ChallengeCollection(Challenge::all());
    }

    public function show(int $challengerId)
    {
        return new ChallengeResource($this->getChallenge($challengerId));
    }

    private function getChallenge(int $challengerId)
    {
        return Challenge::find($challengerId);
    }


}

