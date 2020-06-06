<?php

namespace Socrates\Http\Controllers\Uptime;

use Socrates\Http\Controllers\Controller;
use Socrates\Services\Socrates\Services\Socrates;
use Socrates\Services\Socrates\Uptime;
use BotMan\BotMan\BotMan;

class ShowController extends Controller
{

    /** @var \Socrates\Services\Socrates\Services\Socrates */
    protected $dear;

    public function __construct(Socrates $dear)
    {
        $this->dear = $dear;
    }

    /**
     * Handle the incoming request.
     *
     * @param \BotMan\BotMan\BotMan $bot
     * @param string $url
     *
     * @return void
     * @throws \Socrates\Exceptions\SiteNotFoundException
     */
    public function __invoke(BotMan $bot, string $url)
    {
        $bot->types();

        $site = $this->dear->findSite($url);

        $uptime = $this->dear->getSiteUptime($site->id);

        $daysWithDowntime = $uptime->filter(function (Uptime $uptime) {
            return $uptime->uptimePercentage !== 100;

        })->each(function (Uptime $uptime) use ($bot) {

            $bot->reply(trans('socrates.uptime.result', [
                'percentage' => $uptime->uptimePercentage,
                'date' => $uptime->datetime,
                'emoji' => $uptime->getPercentageEmoji()
                ]));

        });

        if ($daysWithDowntime->isEmpty()) {
            $firstDay = $uptime->reverse()->first();
            $lastDay = $uptime->first();
            $bot->reply(trans('socrates.uptime.perfect', [
                'begin' => $firstDay->datetime, 'end' => $lastDay->datetime
            ]));
        }

        $bot->reply($site->getKeyboard());
    }


}
