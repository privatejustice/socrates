<?php

namespace Socrates\Http\Controllers\Users;

use Socrates\Conversations\StartConversation;
use Socrates\Http\Controllers\Controller;
use BotMan\BotMan\BotMan;

class StoreController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param \BotMan\BotMan\BotMan $bot
     *
     * @return void
     */
    public function __invoke(BotMan $bot)
    {
        $bot->startConversation(new StartConversation());
    }
}
