<?php

namespace Socrates\Http\Controllers\Token;

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
    public function __invoke(BotMan $bot, $token)
    {
        auth()->user()->token = encrypt($token);
        auth()->user()->save();

        $bot->reply(trans('socrates.token.stored'));
    }
}
