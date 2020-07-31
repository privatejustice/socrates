<?php
namespace Socrates\Chat;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;

class Listen {

  static function create($driver, $verifyMode = false) {

    $botman = Socrates\Chat\Botman::get($driver);

    if(!$verifyMode){

      $botman->middleware->received(new Socrates\Chat\Middleware_Identify());
      $botman->middleware->received(new Socrates\Chat\Middleware_RecordIncoming());
      $botman->middleware->sending(new Socrates\Chat\Middleware_RecordOutgoing());

      $botman->hears('(.*)', function ($bot, $hears) {

        $hear = new Socrates\Bao\ChatHear;
        $hear->text = $hears;
        if($hear->find() == 1){
          $hear->fetch();
          socrates_api3('Contact', 'start_conversation', [
            'id' => $bot->getMessage()->getExtras('contact_id'),
            'source_contact_id' => $bot->getMessage()->getExtras('contact_id'),
            'service' => Socrates\Chat\Driver::getServiceName($bot->getDriver()),
            'conversation_type_id' => Socrates\Bao\ChatConversationType::findById($hear->chat_conversation_type_id)->id
          ]);
        }
      });
    }

    $botman->listen();

  }

}
