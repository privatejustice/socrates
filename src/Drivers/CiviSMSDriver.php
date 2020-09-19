<?php

namespace Socrates\Drivers;

use BotMan\BotMan\Users\User;
use BotMan\BotMan\Drivers\HttpDriver;
use BotMan\BotMan\Messages\Incoming\Answer;
use Symfony\Component\HttpFoundation\Request;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Symfony\Component\HttpFoundation\ParameterBag;
use Api;

/**
 * Chat service for developers
 */
class CiviSMSDriver extends HttpDriver
{

    const DRIVER_NAME = 'Socrates\Chat\Driver_CiviSMS';

    const KNOWS_CONTACT_ID = true;

    /**
     * Build a payload for an incoming message from an http request
     *
     * @param Request $request [description]
     */

    public function buildPayload(Request $request)
    {

        $this->payload = new ParameterBag((array) json_decode($request->getContent(), true));

    }

    /**
     * Determine if the request is for this driver (called after buildPayload so we
     * can perform tests based on anything we have set there is required)
     */
    public function matchesRequest()
    {

        return $this->payload->get('authentication_token') == Api::render('setting', 'getvalue', ['name' => 'chatbot_civisms_authentication_token']);

    }

    /**
     * Get the incoming messages from the incoming payload
     *
     * @return [type] [description]
     */
    public function getMessages()
    {

        $text = $this->payload->get('text');
        $userId = $this->payload->get('contact_id');
        $message = new IncomingMessage($text, $userId, $userId);
        $message->addExtras('contact_id', $userId);
        $this->messages = [$message];
        return $this->messages;

    }

    /**
     * Gets the user from the message
     */
    public function getUser(IncomingMessage $matchingMessage)
    {
        return new User($this->payload->get('contact_id'));
        // For this driver, all messages all come from Mr Dev Chat.

    }

    /**
     * Ensure that the driver is configured. We could check that a default SMS
     * provider is available and that chatbot_civisms_authentication_token is set
     */
    public function isConfigured()
    {

        return true;

    }

    /**
     * Only useful for 'interactive' conversations but needs to be defined for all
     * drivers
     */
    public function getConversationAnswer(IncomingMessage $message)
    {

        return Answer::create($message->getText())->setMessage($message);

    }

    /**
     * Construct the outgoing message payload for CivSMS
     */
    public function buildServicePayload($message, $matchingMessage, $additionalParameters = [])
    {
        return [
        'contact_id' => $additionalParameters['contact_id'],
        'message' => ['text' => $message->getText()]
        ];
    }

    /**
     * Send an SMS
     */
    public function sendPayload($payload)
    {

        $contactId = $payload['contact_id'];
        $text = $payload['message']['text'];

        $contactsResult = socrates_api('Contact', 'get', array('version'=>3, 'id' => $contactId));
        $contactDetails = $contactsResult['values'];

        foreach($contactDetails as $contact){
            $contactIds[]=$contact['contact_id'];
        }

        // use the default SMS provider
        $providers = \Socrates\SMS_Bao\Provider::getProviders(null, array('is_default' => 1));
        $provider = current($providers);
        $provider['provider_id'] = $provider['id'];

        $activityParams['sms_text_message']=$text;
        $activityParams['activity_subject']="$text (sent via chatbot)";

        $userId = 1;

        $sms = \Socrates\Activity_Bao\Activity::sendSMS($contactDetails, $activityParams, $provider, $contactIds, $userId);
    }

    /**
     * Low-level method to perform driver specific API requests (not implemented)
     */
    public function sendRequest($endpoint, array $parameters, IncomingMessage $matchingMessage)
    {

    }
}
