<?php

namespace App\Util;

interface IFileWriter
{
    public function write(string $fileName, string $content): void;

    public function exists(string $fileName): bool;

    public function delete(string $fileName): void;

    public function get(string $fileName): string;

    public function cdDir(string $directory): IFileWriter;

    public function cdDirs($directory): IFileWriter;
}
