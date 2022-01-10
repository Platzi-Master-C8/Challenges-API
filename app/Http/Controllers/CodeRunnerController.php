<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CodeRunnerController extends Controller
{
    //Start docker if not running
    public function index()
    {
        try {

            // THis docker Id could help us a lot in the future to save the docker id of each user
            $docker_id = shell_exec('docker run -d --name docker-user_name  -v ' . storage_path() . '/app/ChallengesTests/javascript' . ':/usr/src/app/tests challenges/node');
            $out = Storage::disk('local')->get('ChallengesTests/javascript/cats_0151/template.js');
            //Todo: Reformat to adapt it with user and resolved challenge

        } catch (\Exception $e) {
            $out = $e->getMessage();
        }

        return view('codeRunner', ['template' => $out]);
    }

    public function runNode(Request $request)
    {
//        $user_id = $request->user()->challenger->id;
//        $challengeId = $request->challenge_id;
//        $challengeName = $request->challenge_name;

        try {
            //Storage path will change when we will be using sessions, check NOTION folder structure defined to understand this at all.
            Storage::disk('local')->put('ChallengesTests/javascript/cats_0151/user_1564/user_result.js', $request->code);
            //Todo: Reformat adapt it with user and resolved challenge

        } catch (\Exception $e) {


            return response()->json(['error' => $e->getMessage()]);
        }
        // Run specified test from docker and redirect output to a file, that file have to be binded with local storage
        $command = "docker exec docker-user_name  sh -c 'npm run test tests/cats_0151/user_1564/func_0151_1564.test.js -- --json  > tests/cats_0151/user_1564/test.json'";
        //Todo: Reformat adapt it with user and resolved challenge

        shell_exec($command);


        try {
            $path = storage_path() . '/app/ChallengesTests/javascript/cats_0151/user_1564/test.json';

            // npm run jest -- --json return a string with 4 extra unnecessary lines, just trim them
            $this->trimline($path, 4);
            $result = Storage::disk('local')->get('ChallengesTests/javascript/cats_0151/user_1564/test.json');
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }


        return view('codeRunner', ['json' => json_decode($result),
            'template' => $request->code]);
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
}
