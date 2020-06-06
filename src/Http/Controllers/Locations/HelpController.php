<?php

namespace Socrates\Http\Controllers;

use Socrates\Conversations\RegisterConversation;
use Socrates\Conversations\Girocleta\Station;
use Socrates\Outgoing\OutgoingMessage;
use Socrates\Services\StationService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Location;
use Illuminate\Support\Facades\Log;

class HelpController extends Controller
{

    /**
     * Shows current station information.
     *
     * @param \BotMan\BotMan\BotMan $bot
     *
     * @return mixed
     */
    public function index(BotMan $bot)
    {

        return $bot->reply(file_get_contents(resource_path('help.md')), [
            'parse_mode' => 'Markdown'
        ]);
    }

}
