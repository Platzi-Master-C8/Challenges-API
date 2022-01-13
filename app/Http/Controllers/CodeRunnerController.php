<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Models\Challenge;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CodeRunnerController extends Controller
{
    //Start docker if not running
    public function getChallengeEditor(int $challenge)
    {
        $userIdentifier = $this->getUserIdentifier();
        $challenge = Challenge::find($challenge);

        $dockerName = "docker-" . $userIdentifier;
        shell_exec("docker run -d --name $dockerName  -v "
            . storage_path()
            . "/app/ChallengesTests/javascript" . ':/usr/src/app/tests challenges/node');


        $testFileContent = "const func = require('./user_func.js');\n" . $challenge->test_template;
        $baseUserTestPath = "ChallengesTests/javascript/" . $challenge->id . "/" . $userIdentifier;

        //In case that test has been updated in database. Test file will be rewritten
        Storage::disk('local')
            ->put($baseUserTestPath . '/func.test.js'
                , $testFileContent);


        $userFuncPath = $baseUserTestPath . '/user_func.js';
        //Create user_func file if not exists
        if (!Storage::disk('local')->exists($userFuncPath)) {
            Storage::disk('local')
                ->put($userFuncPath, $challenge->func_template);
        }

        return view('codeRunner', ['template' => $challenge->func_template, 'challenge_id' => $challenge->id]);
    }


    public function runNode(Request $request)
    {

        $userIdentifier = $this->getUserIdentifier();
        $language = !is_null($request->language) ? $request->language : 'javascript';
        $challenge = Challenge::find($request->challenge_id);

        try {
            Storage::disk('local')
                ->put('ChallengesTests/' . $language . '/' . $challenge->id . '/'
                    . $userIdentifier . '/user_func.js', $request->code);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        Storage::disk('local')->put('ChallengesTests/' . $language . '/' . $challenge->id . '/'
            . $userIdentifier . '/test.json', '');

        $command = "docker exec docker-$userIdentifier  sh -c 'npm run test tests/$challenge->id/$userIdentifier/func.test.js -- --json"
            . "> tests/$challenge->id/$userIdentifier/test.json'";

        $result = shell_exec($command);
        try {
            $path = storage_path() . "/app/ChallengesTests/$language/$challenge->id/$userIdentifier/test.json";

            // npm run jest -- --json return a string with 4 extra unnecessary lines, just trim them
            $this->trimline($path, 4);
            $result = Storage::disk('local')->get("ChallengesTests/$language/$challenge->id/$userIdentifier/test.json");
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }


        return view('codeRunner', ['json' => json_decode($result),
            'template' => $request->code, 'challenge_id' => $request->challenge_id]);
    }


    /**
     * @param $file : path to the file to trim
     * @param $trim : number of lines to trim
     * @return void
     * Trim first $trim lines from the $file
     */
    private function trimLine($file, $trim)
    {
        $lines = file($file);
        $content = array_slice($lines, $trim);
        file_put_contents($file, $content);
    }

    /**
     * @return string
     * Return a identifier to have always the same path for user tests
     */
    private function getUserIdentifier(): string
    {
        $user = Auth::user();
        return !is_null($user) ? $user->challenger->id . $user->nick_name : 'guest';
    }
}
