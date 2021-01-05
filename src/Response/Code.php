<?php

namespace Startcode\CleanCore\Response;

use Startcode\CleanCore\Constants\Http;

class Code
{
    public static function asMessage($code) : string
    {
        return Http::$messages[$code] ?? 'Unknown';
    }

    public static function isValid($code) : bool
    {
        return array_key_exists($code, Http::$messages);
    }
}
