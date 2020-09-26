<?php
namespace Socrates\Chat;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;

class Webhook
{

    public static function facebook(): void
    {
        Socrates\Chat\Listen::create('Facebook', $_SERVER['REQUEST_METHOD'] == 'GET');
    }

    public static function devchat(): void
    {
        Socrates\Chat\Listen::create('DevChat');
    }

    public static function civisms(): void
    {
        Socrates\Chat\Listen::create('CiviSMS');
    }
}
