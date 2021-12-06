<?php

namespace App\Constants;

class ChallengeStatuses
{
    public const IN_PROGRESS = 'in_progress';
    public const INCOMPLETE = 'incomplete';
    public const COMPLETE = 'complete';

    public static function toArray(): array
    {
        return [
            self::IN_PROGRESS,
            self::INCOMPLETE,
            self::COMPLETE
        ];
    }
}
