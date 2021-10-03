<?php

namespace Socrates\Helpers;

use Illuminate\Support\Carbon;

class Str
{

    const PERIOD_INTERVALS = [
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second',
    ];

    public static function validate_url(string $url): bool
    {
        $regex = '/(https?:\/\/www\.|https?:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?/';

        return preg_match($regex, $url) === 1;
    }

    /**
     * @param string $search
     * @param string $replace
     *
     * @return string
     */
    public static function replace_last(string $search, string $replace, string $subject): string
    {
        $pos = strrpos($subject, $search);

        if ($pos === false) {
            return $subject;
        }

        return substr_replace($subject, $replace, $pos, strlen($search));

    }

    public static function plural(string $word, int $count): string
    {
        if ($count > 1) {
            $word .= 's';
        }

        return $word;
    }

    /**
     * @return null|string
     */
    public static function elapsed_time_greatest(Carbon $date)
    {
        foreach (self::PERIOD_INTERVALS as $interval) {
            $elapsedTime = $date->{'diffIn' . ucfirst($interval).'s'}();

            if ($elapsedTime) {
                return $elapsedTime . ' ' . self::plural($interval, $elapsedTime);
            }
        }
    }

    public static function elapsed_time(Carbon $begin, Carbon $end): string
    {
        $diff = $end->getTimestamp() - $begin->getTimestamp();

        $diff = $diff < 1 ? 1 : $diff;

        $string = '';
        $separator = '';

        foreach (self::PERIOD_INTERVALS as $unit => $interval) {
            if ($diff < $unit) {
                continue;
            }

            $elapsedInterval = floor($diff / $unit);

            $string .= $separator . $elapsedInterval . ' ' . self::plural($interval, $elapsedInterval);

            $diff -= $elapsedInterval * $unit;

            $separator = ', ';
        }

        return self::replace_last(',', ' and', $string);
    }
}