<?php

namespace Socrates\Conversations\Interactions;

use Boravel\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Attachments\Location as BotManLocation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class Station
{

    /**
     * @var int 
     */
    public $id;

    /**
     * @var string 
     */
    public $name;

    /**
     * @var \Socrates\Conversations\Interactions\Location 
     */
    public $location;

    /**
     * @var int 
     */
    public $parkings;

    /**
     * @var int 
     */
    public $bikes;

    /**
     * @var string 
     */
    public $foundBy;

    /**
     * @return Button
     */
    public function asButton(): Button
    {
        return Button::create($this->name)->value($this->id);
    }

    public function foundById(): self
    {
        $this->foundBy = 'id';

        return $this;
    }

    public function foundByAddress(): self
    {
        $this->foundBy = 'address';

        return $this;
    }

    public function getVenueAddress(): string
    {
        $text = "{$this->bikes} 🚲 - {$this->parkings} 🅿️";

        if (isset($this->distance)) {
            $text .= " - {$this->distance}km";
        }

        return $text;
    }

}