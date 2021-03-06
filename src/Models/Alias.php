<?php

namespace Socrates\Models;

use Socrates\Services\StationService;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class Alias extends Model
{
    protected $table = 'aliases';

    public function getInfo()
    {
        return "{$this->alias} = {$this->getStationName()}";
    }

    public function getStationName()
    {
        return app(StationService::class)->find($this->station_id)->name;
    }

    public function asButton()
    {
        return Button::create($this->getInfo())->value($this->id);
    }

}
