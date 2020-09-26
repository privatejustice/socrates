<?php

namespace Socrates\Services\Socrates;

use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class Site extends \Socrates\Resources\Site
{

    public function __construct(array $attributes, $socrates = null)
    {
        parent::__construct($attributes, $socrates);

        $this->checks = collect($this->checks)->map(
            function ($check) {
                return new Check($check->attributes, $this->socrates);
            }
        );
    }

    public function getResume(): string
    {
        return "{$this->getStatusEmoji()} {$this->sortUrl}";
    }

    public function getInformation(): string
    {
        return "{$this->getStatusEmoji()} {$this->sortUrl}"
            . PHP_EOL
            . collect($this->checks)->map(
                function (Check $check) {

                    if (! $check->enabled) {
                        return null;
                    }

                    return "{$check->getResultAsIcon()} {$check->getTypeAsTitle()}";

                }
            )->filter()->implode(PHP_EOL);
    }

    public function getKeyboard(): Question
    {
        return (new Question(trans('socrates.sites.next_action')))
            ->addButtons(
                [
                Button::create('Uptime')->value("/uptime {$this->id}"),
                Button::create('Downtime')->value("/downtime {$this->id}"),
                Button::create('Broken Links')->value("/brokenlinks {$this->id}"),
                Button::create('Mixed Content')->value("/mixedcontent {$this->id}"),
                ]
            );
    }

    public function isUp(): bool
    {
        return $this->summarizedCheckResult === 'succeeded';
    }

    public function delete()
    {
        return $this->socrates->deleteSite($this->id);
    }

    public function getStatusEmoji(): string
    {
        return $this->isUp() ? "✅" : "🔴";
    }
}