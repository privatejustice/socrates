<?php

namespace Socrates\Conversations\Girocleta;

use Socrates\Outgoing\OutgoingMessage;
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
     * @var \Socrates\Conversations\Girocleta\Location 
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

    public static function createFromPayload($payload)
    {
        $station = new Station();

        $station->id = $payload['id'];
        $station->name = $payload['name'];
        $station->location = new Location($payload['latitude'], $payload['longitude']);
        $station->parkings = $payload['parkings'];
        $station->bikes = $payload['bikes'];

        return $station->foundById();
    }

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

    public function foundByText(): self
    {
        $this->foundBy = 'text';

        return $this;
    }

    public function foundByAlias(): self
    {
        $this->foundBy = 'alias';

        return $this;
    }

    public function foundByAddress(): self
    {
        $this->foundBy = 'address';

        return $this;
    }

    public function wasFoundBy(string $found): bool
    {
        return $this->foundBy == $found;
    }

    public function getVenueMessage(): OutgoingMessage
    {
        $message = new OutgoingMessage();

        $message->withAttachment(new BotManLocation($this->location->latitude, $this->location->longitude));

        return $message;
    }

    /**
     * @return (mixed|string)[]
     *
     * @psalm-return array{title: string, address: mixed}
     */
    public function getVenuePayload(): array
    {
        return [
            'title'   => $this->name,
            'address' => $this->getVenueAddress(),
        ];
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