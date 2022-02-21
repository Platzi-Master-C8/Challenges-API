<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StoreChallengeRequest;
use App\Models\Challenge;
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

    public function store(StoreChallengeRequest $request)
    {
        $request->validated();
        if ($request->token != env('STORE_CHALLENGES_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $challenge = Challenge::create($request->all());
        return new ChallengeResource($challenge);
    }
}
