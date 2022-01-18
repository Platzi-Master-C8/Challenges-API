<?php


namespace App\Constants;

class StorageDisks
{
    const PUBLIC_DISK = 'public';
    const PRIVATE_DISK = 'private';
    const TEMP_DISK = 'temp';
    const LOCAL_DISK = 'local';

    public static function toArray(): array
    {
        return [
            self::PUBLIC_DISK,
            self::PRIVATE_DISK,
            self::TEMP_DISK,
            self::LOCAL_DISK
        ];
    }
}
