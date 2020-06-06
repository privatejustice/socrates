<?php

namespace Socrates\Http\Controllers;

use Socrates\Conversations\ReminderConversation;
use Socrates\Conversations\WelcomeConversation;
use Socrates\Conversations\Girocleta\StationService;
use BotMan\BotMan\BotMan;

class BotManController extends Controller
{

    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }
}
