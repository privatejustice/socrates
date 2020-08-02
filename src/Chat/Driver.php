<?php
namespace Socrates\Chat;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;

class Driver {

  const SHORT_NAMES = [
    'Socrates\Chat\Driver_CiviSMS' => 'CiviSMS',
    'Socrates\Chat\Driver_DevChat' => 'DevChat',
    'Facebook'                     => 'Facebook',
    'Telegram'                     => 'Telegram',
    'Slack'                        => 'Slack',
    'AmazonAlexa'                  => 'AmazonAlexa',
    'Web'                          => 'Web',
  ];

  static function getServiceName($driver) {


    if(!isset(self::SHORT_NAMES[$driver::DRIVER_NAME])) {
      if (\is_object($driver)) {
        $driver = get_class($driver);
      }
      throw new \Exception('Could not find short name for Socrates chatbot driver: ' . $driver::DRIVER_NAME);
    }

    return self::SHORT_NAMES[$driver::DRIVER_NAME];

  }

}
