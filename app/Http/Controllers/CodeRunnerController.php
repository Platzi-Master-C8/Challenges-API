<?php

namespace App\Http\Controllers;

use App\Constants\ChallengeStatuses;
use App\Factories\CodeRunner\CodeRunnerFactory;
use App\Http\Resources\V1\ChallengeResource;
use App\Models\Challenge;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CodeRunnerController extends Controller
{
    /**
     * @param Challenge $challenge
     * @param string $engine
     * @return ChallengeResource|string
     * @throws Exception
     */
    public function getChallengeEditor(string $engine, Challenge $challenge)
    {
        $userIdentifier = $this->getUserIdentifier();

        try {
            $runner = CodeRunnerFactory::create($engine);
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }

        $runner->prepareRunner($challenge, $userIdentifier);
        return new ChallengeResource($challenge);
    }

    /**
     * @throws Exception
     */
    public function run(Request $request, string $engine, Challenge $challenge): string
    {
        $userIdentifier = $this->getUserIdentifier();


        try {
            $runner = CodeRunnerFactory::create($engine);
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
        $result = $runner->run($userIdentifier, $challenge, $request->get('code'));
        return json_encode(['test_result' => $result]);
    }


    public function submit(Request $request): JsonResponse
    {

        $challenger = User::find($request->userId)->challenger;
        $challenge = Challenge::find($request->challengeId);

        //Todo: Implement observer pattern to replace this
        $runner = CodeRunnerFactory::create($request->engine);
        $runner->stop($this->getUserIdentifier());

        if (($challenger && $challenge)) {
            if ($challenger->challenges()->where('challenge_id', $challenge->id)->first()) {
                $challenger->challenges()->updateExistingPivot($challenge, ['status' => ChallengeStatuses::COMPLETE]);
            } else {
                $challenger->challenges()->attach($challenge->id, ['status' => ChallengeStatuses::COMPLETE]);
            }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    private function getUserIdentifier(): string
    {
        $user = Auth::user();
        return !is_null($user) ? $user->challenger->id . $user->nick_name : 'guest';
    }

}
