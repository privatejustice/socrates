<?php

namespace Socrates\Services\Socrates;

use Illuminate\Support\Carbon;
use Socrates\Resources\ApiResource;

class Uptime extends ApiResource
{

    const PERCENTAGES_EMOJIS = [
        '🎉' => 100,
        '🙌' => 75,
        '😕' => 50,
        '😞' => 25,
        '😱' => 0,
    ];

    public function __construct(array $attributes, $socrates = null)
    {
        parent::__construct($attributes, $socrates);

        $this->datetime = Carbon::parse($this->datetime);
    }

    public function getPercentageEmoji()
    {
        foreach (self::PERCENTAGES_EMOJIS as $emoji => $cut) {

            if (abs($this->uptimePercentage - $cut) <= 12.5) {
                return $emoji;
            }
        }

        return '😱';
    }

}