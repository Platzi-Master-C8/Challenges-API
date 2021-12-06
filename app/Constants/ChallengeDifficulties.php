<?php

namespace App\Constants;

class ChallengeDifficulties
{
    public const LOW = 'low';
    public const MEDIUM = 'medium';
    public const HIGH = 'high';

    public static function toArray(): array
    {
        return [
            self::LOW,
            self::MEDIUM,
            self::HIGH
        ];
    }
}
