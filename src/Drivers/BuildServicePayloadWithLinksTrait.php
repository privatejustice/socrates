<?php

namespace Socrates\Drivers;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Socrates\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;

trait BuildServicePayloadWithLinksTrait
{
    /**
     * Expand Telegram Driver behaviour to allow external link buttons on messages
     *
     * @param string|Question|OutgoingMessage                  $message
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $matchingMessage
     * @param array                                            $additionalParameters
     *
     * @return Response
     */
    public function buildServicePayload($message, $matchingMessage, $additionalParameters = [])
    {
        $parameters = parent::buildServicePayload($message, $matchingMessage, $additionalParameters);

        if ($this->haveCustomLinks($message)) {
            $parameters['reply_markup'] = json_encode(
                [
                'inline_keyboard' => $this->convertLinks($message->getActions()),
                ], true
            );
        }

        return $parameters;

    }

    private function haveCustomLinks($message): bool
    {
        return method_exists($message, 'getActions')
            && $message instanceof OutgoingMessage
            && count($message->getActions());
    }

    private function convertLinks(array $actions): array
    {
        return Collection::make($actions)->map(
            function ($action) {
                return [$action];
            }
        )->toArray();
    }
}