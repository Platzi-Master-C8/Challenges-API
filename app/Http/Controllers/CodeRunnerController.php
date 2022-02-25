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
     * @param Request $request
     * @param string $engine
     * @param Challenge $challenge
     * @return ChallengeResource|string
     */
    public function getChallengeEditor(Request $request, string $engine, Challenge $challenge)
    {
        $userIdentifier = $this->getUserIdentifier($request->header('user'));

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
        $challenger = User::find($request->header('user'))->challenger;
        if (!$challenger->challenges()->where('challenge_id', $challenge->id)->first()) {
            $challenger->challenges()->attach($challenge->id, ['status' => ChallengeStatuses::IN_PROGRESS]);
        }
        $user = $this->getUserIdentifier($request->header('user'));
        $result = $this->runCode($engine, $request->get('code'), $user, $challenge);
        return json_encode(['test_result' => $result]);
    }


    public function submit(Request $request): JsonResponse
    {
        $challenger = User::find($request->header('user'))->challenger;
        $challenge = Challenge::find($request->challengeId);
        $runner = CodeRunnerFactory::create($request->engine);
        $runner->stop($this->getUserIdentifier($request->header('user')));
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

    private function getUserIdentifier($user): string
    {
        $user = User::where('id', $user)->first();
        return !is_null($user) ? $user->challenger->id . $user->nick_name : 'guest';
    }

    private function runCode($engine, $code, $user, $challenge)
    {
        try {
            $runner = CodeRunnerFactory::create($engine);
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
        return $runner->run($user, $challenge, $code);
    }

}
