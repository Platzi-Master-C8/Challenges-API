<?php

namespace App\Constants;

class LocalChallengesPaths implements IConstant
{
    public const NODE_PATH = "/app/ChallengesTests/javascript";

    public static function toArray(): array
    {
        return [
            storage_path() . self::NODE_PATH,
        ];
    }
}
