<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CodeRunnerController extends Controller
{
    //Lets start docker if
    public function index()
    {
        try {
            $docker_id = shell_exec('docker run -d --name docker-user_name  -v ' . storage_path() . '/app/ChallengesTests/javascript' . ':/usr/src/app/tests challenges/node');

            $out = Storage::disk('local')->get('ChallengesTests/javascript/cats_0151/template.js');
        } catch (\Exception $e) {
            $out = $e->getMessage();
        }

        return view('codeRunner', ['template' => $out]);
    }

    public function runNode(Request $request)
    {
        Storage::disk('local')->put('ChallengesTests/javascript/cats_0151/user_1564/user_result.js', $request->code);


        $route = "docker exec docker-user_name  sh -c 'npm run test tests/cats_0151/user_1564/func_0151_1564.test.js -- --json  > tests/cats_0151/user_1564/test.json'";
        shell_exec($route);
        try {
            $result = Storage::disk('local')->get('ChallengesTests/javascript/cats_0151/user_1564/test.json');
        } catch (FileNotFoundException $e) {
            $result = $e->getMessage();
        }
        return view('codeRunner', ['template' => $result]);
    }
}
