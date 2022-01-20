<?php

namespace App\Constants;

class LocalChallengesPaths implements IConstant
{
    public const NODE_PATH = "/app/ChallengesTests/javascript";

    public static function toArray(): array
    {
        return [
            self::NODE_PATH,
        ];
    }
}
