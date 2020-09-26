<?php


namespace Socrates\Outgoing;

use BotMan\BotMan\Messages\Outgoing\OutgoingMessage as BotManOutgoingMessage;

class OutgoingMessage extends BotManOutgoingMessage
{

    protected $actions = [];

    public function getActions(): array
    {
        return $this->actions;
    }
}