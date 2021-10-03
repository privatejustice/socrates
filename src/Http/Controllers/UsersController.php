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
     *
     * @return void
     */
    public function destroy(BotMan $bot): void
    {
        $bot->startConversation(new DeleteUserConversation());
    }
}
