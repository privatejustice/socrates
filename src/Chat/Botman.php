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
    static function getAllServices()
    {
        $services = [
        'Facebook' => 'Facebook',
        'CiviSMS' => 'CiviSMS',
        ];
        if(Civi::settings()->get('debug_enabled')) {
            $services['DevChat'] = 'DevChat';
        }
        return $services;
    }

    static function get($service)
    {

        $driver = self::getDriver($service);
        $config = self::getConfig($service);


        DriverManager::loadDriver($driver);
        $botman = BotManFactory::create($config, new \Socrates\Chat\Cache);

        return $botman;

    }

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
