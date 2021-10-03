<?php

namespace Socrates\Http\Controllers;

use Socrates\Conversations\CreateAliasConversation;
use Socrates\Conversations\DeleteAliasConversation;
use Socrates\Models\Alias;
use Socrates\Services\StationService;
use BotMan\BotMan\BotMan;

class AliasesController extends Controller
{

    /**
     * @var \Socrates\Services\StationService 
     */
    protected $stationService;

    public function __construct()
    {
        $this->stationService = app(StationService::class);
    }

    public function index(BotMan $bot)
    {
        if (!auth()->user()) {
            return $bot->reply('Alises Controller deveria estar logado'); // @todo
        }

        $aliases = Alias::where('user_id', auth()->user()->id)->get();

        if (! $aliases->count()) {
            return $bot->reply('Encara no tens cap alias, pots afegir-ne un amb /alias');
        }

        $bot->reply('Aquests són els teus alias');

        $aliases->each(
            function (Alias $alias) use ($bot) {
                $bot->reply($alias->getInfo());
            }
        );

    }

    /**
     * Start a conversation to register new reminder.
     *
     * @param \BotMan\BotMan\BotMan $bot
     *
     * @return void
     */
    public function create(BotMan $bot): void
    {
        $bot->startConversation(new CreateAliasConversation());
    }

    /**
     * Start a conversation to delete a reminder.
     *
     * @param \BotMan\BotMan\BotMan $bot
     *
     * @return void
     */
    public function destroy(BotMan $bot): void
    {
        $bot->startConversation(new DeleteAliasConversation());
    }
}
