<?php
namespace Socrates\Chat\Middleware;

use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

class RecordOutgoing extends \Socrates\Chat\Middleware implements Sending {

  public function sending($payload, $next, BotMan $bot) {

    $details = $payload['message']['text'];
    $subject = Socrates\Chat\Driver::getServiceName($bot->getDriver()) . ': ' . Socrates\ChatUtils::shorten($details, 50);
    $contactId = $bot->getMessage()->getExtras('contact_id');
    $conversationId = $bot->getMessage()->getExtras('conversation_id');

    socrates_api3('activity', 'create', [
      'activity_type_id' => 'Outgoing chat',
      'subject' => $subject,
      'details' => $details,
      'target_contact_id' => $contactId,
      'source_contact_id' => $contactId,
      'parent_id' => Socrates\Chat\Utils::getOngoingConversation($contactId)['id']
    ]);

    return $next($payload);
  }

}
