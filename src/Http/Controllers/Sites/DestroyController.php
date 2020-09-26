<?php

namespace Socrates\Http\Controllers\Sites;

use Socrates\Conversations\SiteDestroyConversation;
use Socrates\Http\Controllers\Controller;
use Socrates\Services\Socrates\Services\Socrates;
use BotMan\BotMan\BotMan;

class DestroyController extends Controller
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
        $site = $this->dear->findSite($url);

        $bot->startConversation(new SiteDestroyConversation($this->dear, $site));
    }
}
