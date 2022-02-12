<?php

namespace App\Util;


class FileHelper
{

    /**
     * @param $file:  path to the file to trim
     * @param $trim : number of lines to trim
     * @return void
     * Trim first $trim lines from the $file
     */
    public static function trimLines($file, $trim)
    {
        $lines = file($file);
        $content = array_slice($lines, $trim);
        file_put_contents($file, $content);
    }
}
