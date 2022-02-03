<?php

namespace App\Util;

use App\Constants\AvailableDockerLanguages;
use Exception;

class JsonTestParser
{
    /**
     * @param $json : Complex array with json data
     * @param $language : Language of the test, use App\Constants\AvailableDockerLanguages
     * @throws Exception : If the language is not supported
     */
    public static function parse($json, string $language): array
    {
        $availableLanguages = AvailableDockerLanguages::toArray();

        if (!in_array($language, $availableLanguages)) {
            throw new Exception('Language not supported');
        }
        switch ($language) {
            case AvailableDockerLanguages::JS:
                return self::parseJs($json);
            case AvailableDockerLanguages::PHP:
                return self::parsePhp($json);
            default:
                throw new Exception('Switch is not handling all available languages, fix it!');
        }
    }

    private static function parseJs($json): array
    {
        $data['message'] = $json->testResults[0]->message;
        $data['status'] = $json->testResults[0]->status;

        return $data;
    }

    private static function parsePhp($json): array
    {

        /*Todo: We could implement php test in the future, we know how phpunit works, so it would be very easy for us*/
        return [];
    }
}
