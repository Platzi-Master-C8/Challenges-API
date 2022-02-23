<?php

namespace App\Factories\CodeRunner;

class CodeRunnerFactory
{
    public static function create(string $language): CodeRunner
    {
        switch ($language) {
            case 'node':
                return new JsCodeRunner();
            default:
                throw new \Exception('Language not supported');
        }
    }
}
