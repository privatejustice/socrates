<?php

namespace Socrates\Services;

use Socrates\Models\Reminder;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use Illuminate\Support\Carbon;

class ReminderService
{

    const DAYS_OPTIONS = [
        'all' => 'Tots els dies',
        'weekend' => 'Cap de setmana',
        'weekdays' => 'Entre setmana',
    ];

    const POSSIBLE_TIME_FORMATS = [
        'H:i',
        'h:i',
        'H:i a',
        'h:i a',
        'H i',
        'h i',
        'H',
        'H a',
        'h',
        'h a',
    ];

    /**
     * @return string[]
     *
     * @psalm-return array{bikes: string, parkings: string}
     */
    public function all(): array
    {
        return Reminder::TYPES;
    }

    public function asButtons(): array
    {
        return collect($this->all())->map(
            function ($text, $reminder) {
                return Button::create($text)->value($reminder);
            }
        )->values()->toArray();
    }

    public function find(string $reminder)
    {
        if (! array_key_exists($reminder, $this->all())) {
            return null;
        }

        return $this->all()[$reminder];
    }

    public function possibleDaysButtons(): array
    {
        return collect(self::DAYS_OPTIONS)->map(
            function ($text, $value) {
                return Button::create($text)->value($value);
            }
        )->toArray();
    }

    public function parseHoursFromInput(string $input): ?string
    {
        $input = strtolower($input);

        foreach (self::POSSIBLE_TIME_FORMATS as $format) {
            try {
                return Carbon::createFromFormat($format, $input)->format('H:i');
            } catch(\Exception $e) {
            }
        }

        return null;
    }

    public function parseDaysFromInput(string $input): \Illuminate\Support\Collection
    {
        $days = collect(Reminder::DAYS);

        if ($input === 'all') {
            return $days;
        }

        if ($input === 'weekend') {
            return $days->only('saturday', 'sunday');
        }

        if ($input === 'weekdays') {
            return $days->except('saturday', 'sunday');
        }

        return $days->filter(
            function ($day) use ($input) {

                return str_contains(strtolower($input), strtolower($day));

            }
        );
    }

}