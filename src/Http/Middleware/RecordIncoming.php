<?php
namespace Socrates\Http\Middleware;

use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;
use Api;

class RecordIncoming extends Middleware implements Received
{

    public function received(IncomingMessage $message, $next, BotMan $bot)
    {

        $details = $message->getText();
        \Socrates\Chat\Driver::getServiceName($bot->getDriver()) . ': ' . \Socrates\Chat\Utils::shorten($details, 50);
        $message->getExtras('contact_id');

        Api::render(
            'activity', 'create', [
            'activity_type_id' => 'Incoming chat',
            'subject' => $subject,
            'details' => $details,
            'target_contact_id' => $contactId,
            'source_contact_id' => $contactId,
            'parent_id' => \Socrates\Chat\Utils::getOngoingConversation($contactId)['id']
            ]
        );

        return $next($message);
    }

}
