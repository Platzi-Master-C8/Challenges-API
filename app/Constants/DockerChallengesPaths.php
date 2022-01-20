<?php

namespace App\Constants;


class DockerChallengesPaths implements IConstant
{
    public const NODE_PATH = '/usr/src/app/tests';
    public const PYTHON_PATH = '';

    public static function toArray(): array
    {
        return [
            self::NODE_PATH,
            self::PYTHON_PATH,
        ];
    }
}
