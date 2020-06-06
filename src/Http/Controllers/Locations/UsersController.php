<?php

namespace Socrates\Http\Controllers;

use Socrates\Conversations\DeleteUserConversation;
use BotMan\BotMan\BotMan;

class UsersController extends Controller
{
    /**
     * Forget about the current selected station.
     *
     * @param \BotMan\BotMan\BotMan $bot
     */
    public function destroy(BotMan $bot)
    {
        $bot->startConversation(new DeleteUserConversation());
    }
}
