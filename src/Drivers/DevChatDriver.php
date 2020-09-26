<?php

namespace Socrates\Drivers;

use BotMan\BotMan\Users\User;
use BotMan\BotMan\Drivers\HttpDriver;
use BotMan\BotMan\Messages\Incoming\Answer;
use Symfony\Component\HttpFoundation\Request;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Chat service for developers
 */
class DevChatDriver extends HttpDriver
{

    const DRIVER_NAME = 'Socrates\Chat\Driver_DevChat';

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
     *
     * @return true
     */
    public function matchesRequest()
    {

        return true;

    }

    /**
     * Get the incoming messages from the incoming payload
     *
     * @return IncomingMessage[] [description]
     *
     * @psalm-return array{0: IncomingMessage}
     */
    public function getMessages()
    {

        $message = $this->payload->get('text');
        $userId = 1;
        $this->messages = [new IncomingMessage($message, $userId, $userId)];

        return $this->messages;

    }

    /**
     * Gets the user from the message
     *
     * @return User
     */
    public function getUser(IncomingMessage $matchingMessage)
    {

        // For this driver, all messages all come from Mr Dev Chat.
        return new User('1', 'Joe', 'Bloggs');

    }

    /**
     * Ensure that the driver is configured. We could check that
     * chatbot_devchat_endpoint has been set in this method.
     *
     * @return true
     */
    public function isConfigured()
    {

        return true;

    }

    /**
     * Only useful for 'interactive' conversations but needs to be defined for all
     * drivers
     *
     * @return Answer
     */
    public function getConversationAnswer(IncomingMessage $message)
    {

        return Answer::create($message->getText())->setMessage($message);

    }

    /**
     * Construct the outgoing message payload for DevChat
     *
     * @return string[][]
     *
     * @psalm-return array{message: array{text: string}}
     */
    public function buildServicePayload($message, $matchingMessage, $additionalParameters = []): array
    {

        return ['message' => ['text' => $message->getText()]];

    }

    /**
     * Send the outgoing message payload to DevChat
     */
    public function sendPayload($payload)
    {

        $response = $this->http->post($this->config->get('endpoint'), [], $payload, ['Content-type: application/json'], true);

        return $response;

    }

    /**
     * Low-level method to perform driver specific API requests (not implemented)
     */
    public function sendRequest($endpoint, array $parameters, IncomingMessage $matchingMessage)
    {

    }
}
