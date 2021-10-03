<?php


namespace Socrates\Outgoing;

use BotMan\BotMan\Messages\Outgoing\OutgoingMessage as BotManOutgoingMessage;

class OutgoingMessage extends BotManOutgoingMessage
{

    protected $actions = [];

    /**
     * @return static
     */
    public function addLink($text, $url): self
    {
        $this->actions[] = [
            'text' => $text,
            'url' => $url
        ];

        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }
}