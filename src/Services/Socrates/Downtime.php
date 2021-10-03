<?php

namespace Socrates\Services\Socrates;

use Socrates\Helpers\Str;
use Illuminate\Support\Carbon;
use Socrates\Resources\ApiResource;

class Downtime extends ApiResource
{

    const INTERVALS_EMOJIS = [
        'month' => '🎉',
        'week' => '🙌',
        'day' => '👍',
        'hour' => '😕',
        'minute' => '😞',
        'second' => '😱'
    ];

    public function __construct(array $attributes, $socrates = null)
    {
        parent::__construct($attributes, $socrates);

        $this->startedAt = Carbon::parse($this->startedAt);
        $this->endedAt = Carbon::parse($this->endedAt);
        $this->elapsed = Str::elapsed_time_greatest($this->endedAt);
    }

    public function getDowntime()
    {
        return Str::elapsed_time($this->startedAt, $this->endedAt);
    }

    public function getElapsedEmoji(): string
    {
        foreach (self::INTERVALS_EMOJIS as $key => $emoji) {
            if (stripos($this->elapsed, $key) !== false) {
                return $emoji;
            }
        }

        return '😱';
    }
}