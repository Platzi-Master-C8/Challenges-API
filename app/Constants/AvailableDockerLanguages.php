<?php

namespace App\Constants;

class AvailableDockerLanguages implements IConstant
{
    const JS = 'javascript';
    const PHP = 'php';

    public static function toArray(): array
    {
        return [
            self::JS,
            self::PHP,
        ];
    }
}
