<?php

namespace App\Factories\CodeRunner;

use App\Models\Challenge;

interface CodeRunner
{

    public function run($userId, Challenge $challenge, string $code): array;

    public function prepareRunner(Challenge $challenge, string $userId);

    public function stop(string $userId): void;
}
