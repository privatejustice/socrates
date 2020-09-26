<?php

namespace Socrates\Models;

use Socrates\Services\StationService;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class Alias extends Model
{
    protected string $table = 'aliases';

    public function getInfo(): string
    {
        return "{$this->alias} = {$this->getStationName()}";
    }

    public function getStationName(): ?string
    {
        return app(StationService::class)->find($this->station_id)->name;
    }

    public function asButton(): Button
    {
        return Button::create($this->getInfo())->value($this->id);
    }

}
