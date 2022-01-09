<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CodeRunnerController extends Controller
{
    //Lets start docker if
    public function index()
    {
        try {
            $out = 'cat';
//            $out = shell_exec('docker run -d --name docker-user_name challenges/node');
        } catch (\Exception $e) {
            $out = $e->getMessage();
        }
        return view('codeRunner', ['output' => $out]);
    }

    public function runNode()
    {
        $out = shell_exec('docker ps -a');
        return $out;
    }
}
