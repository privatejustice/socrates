<?php
namespace Socrates\Chat;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;

class Driver {

  const SHORT_NAMES = [
    'Facebook' => 'Facebook',
    'Socrates\Chat\Driver_CiviSMS' => 'CiviSMS',
    'Socrates\Chat\Driver_DevChat' => 'DevChat'
  ];

  static function getServiceName($driver) {

    if(!isset(self::SHORT_NAMES[$driver::DRIVER_NAME])) {
      throw new \Exception('Could not find short name for Socrates chatbot driver: ' . $driver);
    }

    return self::SHORT_NAMES[$driver::DRIVER_NAME];

  }

}
