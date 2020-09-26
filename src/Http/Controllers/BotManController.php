<?php

namespace Socrates\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Socrates\Conversations\ExampleConversation;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     *
     * @return void
     */
    public function handle(): void
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function tinker(): \Illuminate\View\View
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     *
     * @param BotMan $bot
     *
     * @return void
     */
    public function startConversation(BotMan $bot): void
    {
        $bot->startConversation(new ExampleConversation());
    }
}
