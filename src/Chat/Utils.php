<?php
namespace Socrates\Chat;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Api;
/**
 * "Wraps" botman
 */

class Utils
{

    static function shorten(string $string, int $length = 50)
    {

        return strlen($string) > $length ? substr($string, 0, $length)."..." : $string;

    }
}
