<?php

namespace App\Constants;
class DockerImagesNames implements IConstant
{
    public const NODE_IMAGE = 'challenges/node';

    public static function toArray(): array
    {
        return [
            self::NODE_IMAGE,
        ];
    }
}
