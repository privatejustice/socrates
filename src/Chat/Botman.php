<?php
namespace Socrates\Chat;

use BotMan\BotMan\BotMan as BotManBase;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;
use Api;

class Botman
{

    // Might want to turn into option groups at some point


    static function get($service): BotManBase
    {

        $driver = self::getDriver($service);
        $config = self::getConfig($service);


        DriverManager::loadDriver($driver);
        $botman = BotManFactory::create($config, new \Socrates\Chat\Cache);

        return $botman;

    }

    /**
     * @return FacebookDriver::class|Socrates\Chat\Driver_CiviSMSDriver::class|Socrates\Chat\Driver_DevChatDriver::class|null
     */
    static function getDriver($service)
    {

        switch ($service) {

        case 'Facebook':

            return FacebookDriver::class;

        case 'CiviSMS':

            return Socrates\Chat\Driver_CiviSMSDriver::class;

        case 'DevChat':

            return Socrates\Chat\Driver_DevChatDriver::class;

        }

    }

    /**
     * @return (array|mixed)[]|null
     *
     * @psalm-return array{facebook?: array{token: mixed, app_secret: mixed, verification: mixed}, endpoint?: mixed, authentication_token?: mixed}|null
     */
    static function getConfig($service)
    {

        switch ($service) {

        case 'Facebook':
            return [
          'facebook' => [
            'token' => Api::render('setting', 'getvalue', ['name' => 'chatbot_facebook_page_access_token']),
            'app_secret' => Api::render('setting', 'getvalue', ['name' => 'chatbot_facebook_app_secret']),
            'verification' => Api::render('setting', 'getvalue', ['name' => 'chatbot_facebook_verify_token'])
          ]
            ];

        case 'DevChat':
            return [
          'endpoint' => Api::render('setting', 'getvalue', ['name' => 'chatbot_devchat_endpoint'])
            ];

        case 'CiviSMS':

            return [
          'authentication_token' => Api::render('setting', 'getvalue', ['name' => 'chatbot_civisms_authentication_token'])
            ];

        }

    }

}
