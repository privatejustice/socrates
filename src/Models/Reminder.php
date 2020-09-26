<?php

namespace Socrates\Models;

use Socrates\Services\StationService;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use Illuminate\Support\Collection;

class Reminder extends Model
{

    const DAYS = [
        'monday' => 'Dilluns',
        'tuesday' => 'Dimarts',
        'wednesday' => 'Dimecres',
        'thursday' => 'Dijous',
        'friday' => 'Divendres',
        'saturday' => 'Dissabte',
        'sunday' => 'Diumenge'
    ];

    const TYPES = [
        'bikes' => 'Bicis lliures',
        'parkings' => 'Aparcaments lliures'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setDays(Collection $days): self
    {
        $this->monday = $days->has('monday');
        $this->tuesday = $days->has('tuesday');
        $this->wednesday = $days->has('wednesday');
        $this->thursday = $days->has('thursday');
        $this->friday = $days->has('friday');
        $this->saturday = $days->has('saturday');
        $this->sunday = $days->has('sunday');

        return $this;
    }

    public function getDaysList(): string
    {
        $message = '';

        foreach(self::DAYS as $day => $translate) {
            $icon = $this->$day ? '✅' : '❌';

            $message .= "{$icon} {$translate}".PHP_EOL;
        }

        return $message;
    }

    public function asButton(): Button
    {
        $station = app(StationService::class)->find($this->station_id);

        $buttonText = "{$this->type_str} a {$station->name} a les {$this->time} els dies {$this->days_str}";

        return Button::create($buttonText)->value($this->id);
    }

}
