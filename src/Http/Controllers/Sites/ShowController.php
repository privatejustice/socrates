<?php

namespace Socrates\Http\Controllers\Sites;

use Socrates\Http\Controllers\Controller;
use Socrates\Services\Socrates\Services\Socrates;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

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

        $bot->reply($site->getInformation());
        $bot->reply($site->getKeyboard());
    }
}
