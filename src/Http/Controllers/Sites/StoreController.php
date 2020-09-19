<?php

namespace Socrates\Http\Controllers\Sites;

use Socrates\Exceptions\InvalidUrlException;
use Socrates\Http\Controllers\Controller;
use Socrates\Services\Socrates\Services\Socrates;
use BotMan\BotMan\BotMan;

class StoreController extends Controller
{

    /**
     * @var \Socrates\Services\Socrates\Services\Socrates 
     */
    protected $dear;

    public function __construct(Socrates $dear)
    {
        $this->dear = $dear;
    }

    /**
     * Handle the incoming request.
     *
     * @param \BotMan\BotMan\BotMan $bot
     * @param string                $url
     *
     * @return void
     */
    public function __invoke(BotMan $bot, string $url)
    {
        $bot->types();

        if ($this->dear->findSiteByUrl($url)) {
            $bot->reply(trans('socrates.sites.already_exists'));
            return;
        }

        try {
            $site = $this->dear->createSite($url);

            $bot->reply(trans('socrates.sites.created'));

        } catch (InvalidUrlException $e) {
            $bot->reply(trans('socrates.sites.invalid_url'));
        }
    }
}
