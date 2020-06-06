<?php
namespace Socrates\Chat\Middleware;

use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

class RecordIncoming extends \Socrates\Chat\Middleware implements Received {

  public function received(IncomingMessage $message, $next, BotMan $bot) {

    $details = $message->getText();
    $subject = Socrates\Chat\Driver::getServiceName($bot->getDriver()) . ': ' . Socrates\ChatUtils::shorten($details, 50);
    $contactId = $message->getExtras('contact_id');

    socrates_api3('activity', 'create', [
      'activity_type_id' => 'Incoming chat',
      'subject' => $subject,
      'details' => $details,
      'target_contact_id' => $contactId,
      'source_contact_id' => $contactId,
      'parent_id' => Socrates\Chat\Utils::getOngoingConversation($contactId)['id']
    ]);

    return $next($message);
  }

}
