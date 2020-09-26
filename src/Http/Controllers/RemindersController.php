<?php

namespace Socrates\Http\Controllers;

use Socrates\Conversations\CreateReminderConversation;
use Socrates\Conversations\DeleteReminderConversation;
use Socrates\Models\Reminder;
use Socrates\Services\StationService;
use BotMan\BotMan\BotMan;

class RemindersController extends Controller
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
        $reminders = auth()->user()->reminders;

        if (! $reminders->count()) {
            return $bot->reply('Encara no tens cap recordatori, pots afegir-ne un amb /reminder');
        }

        $bot->reply('Aquests són els teus recordatoris');

        $reminders->each(
            function (Reminder $reminder) use ($bot) {
                $bot->reply(
                    "Recorda'm {$reminder->type_str_lower} a {$this->stationService->find($reminder->station_id)->name}" . PHP_EOL .
                    "{$reminder->getDaysList()}" . PHP_EOL .
                    "a les {$reminder->time}"
                );
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
        $bot->startConversation(new CreateReminderConversation());
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
        $bot->startConversation(new DeleteReminderConversation());
    }
}
