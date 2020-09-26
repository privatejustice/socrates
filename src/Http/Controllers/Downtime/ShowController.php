<?php

namespace Socrates\Http\Controllers\Downtime;

use Socrates\Http\Controllers\Controller;
use Socrates\Services\Socrates\Downtime;
use Socrates\Services\Socrates\Services\Socrates;
use BotMan\BotMan\BotMan;

class ShowController extends Controller
{

    /**
     * @var \Socrates\Services\Socrates\Services\Socrates 
     */
    protected $dear;

    /**
     * Handle the incoming request.
     *
     * @param \BotMan\BotMan\BotMan $bot
     * @param string                $url
     *
     * @return void
     * @throws \Socrates\Exceptions\SiteNotFoundException
     */
    public function __invoke(BotMan $bot, string $url)
    {
        $bot->types();

        $site = $this->dear->findSite($url);

        $downtime = $this->dear->getSiteDowntime($site->id);

        if ($downtime->isEmpty()) {
            $bot->reply(trans('socrates.downtime.perfect'));
        } else {

            $bot->reply(
                trans(
                    'socrates.downtime.summary', [
                    'elapsed' => $downtime->first()->elapsed,
                    'emoji' => $downtime->first()->getElapsedEmoji(),
                    ]
                )
            );

            $downtime->each(
                function (Downtime $downtime) use ($bot) {

                    $bot->reply(
                        trans(
                            'socrates.downtime.result', [
                            'downtime' => $downtime->getDowntime(),
                            'date' => $downtime->startedAt,
                            ]
                        )
                    );
                }
            );
        }

        $bot->reply($site->getKeyboard());
    }

}
