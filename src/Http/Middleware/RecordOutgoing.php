<?php
namespace Socrates\Http\Middleware;

use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;
use Api;

class RecordOutgoing extends Middleware implements Sending
{

    public function sending($payload, $next, BotMan $bot)
    {
        // dd($payload);
        // $details = $payload['message']['text'];
        $details = $payload['message']->getText();
        $subject = \Socrates\Chat\Driver::getServiceName($bot->getDriver()) . ': ' . \Socrates\Chat\Utils::shorten($details, 50);
        $contactId = $bot->getMessage()->getExtras('contact_id');
        $conversationId = $bot->getMessage()->getExtras('conversation_id');

        Api::render(
            'activity', 'create', [
            'activity_type_id' => 'Outgoing chat',
            'subject' => $subject,
            'details' => $details,
            'target_contact_id' => $contactId,
            'source_contact_id' => $contactId,
            'parent_id' => \Socrates\Chat\Utils::getOngoingConversation($contactId)['id']
            ]
        );

        return $next($payload);
    }

}
