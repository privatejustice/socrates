<?php

namespace Socrates\Console\Commands;

use Socrates\Drivers\TelegramDriver;
use Socrates\Models\Reminder;
use Socrates\Services\StationService;
use Illuminate\Console\Command;

class SendRemindersCommand extends Command
{

    protected $signature = 'reminders:send';

    protected $description = 'Send the reminders for the current day and time';

    /**
     * @var \Socrates\Services\StationService  
     */
    protected $stationService;

    /**
     * @var \BotMan\BotMan\BotMan 
     */
    protected $botman;

    public function __construct(StationService $stationService)
    {
        parent::__construct();
        $this->stationService = $stationService;
        $this->botman = resolve('botman');
    }

    /**
     * @return void
     */
    public function handle()
    {
        $reminders = $this->getReminders();

        if (! $reminders->count()) {
            return;
        }

        $reminders->each(
            function (Reminder $reminder) {

                $user = $reminder->user;
                $station = $this->stationService->find($reminder->station_id);

                if (! $station || ! $reminder->user) {
                    return; // corrupted reminder, we need to do something about it
                }

                $this->sayTo($this->getGreetings($user->name), $user->telegram_id);
                $this->sayTo($station->getVenueMessage(), $user->telegram_id, $station->getVenuePayload());

                if ($station->bikes == 0) {
                    $this->sayTo("Ja pots anar a la següent estació, aquí no hi ha cap bici 🏃", $reminder->user->telegram_id);
                }

                if ($station->bikes == 1) {
                    $this->sayTo("Ep! Només en queda una! És possible que estigui defectuosa... 🤔", $reminder->user->telegram_id);
                }

            }
        );
    }

    private function getGreetings($name): string
    {
        return "🕐 {$this->getSalute()} {$name}, aquí tens la informació del teu recordatori 👇";
    }

    private function getSalute(): string
    {
        $hour = date('H');

        if ($hour <= 5) {
            return 'Bona nit';
        }

        if ($hour <= 12) {
            return 'Bon dia';
        }

        if ($hour <= 20) {
            return 'Bona tarda';
        }

        return 'Bona nit';
    }

    /**
     * @param (mixed|string)[] $params
     */
    private function sayTo(string $message, $userId, array $params = []): void
    {
        $this->botman->say($message, $userId, TelegramDriver::class, $params);

    }

    private function getReminders()
    {
        return Reminder::where('active', true)
            ->where(date('l'), true)
            ->where('time', date('H:i'))
            ->where('date_begin', '<=', date('Y-m-d H:i:s'))
            ->where(
                function ($dateEnd) {
                    return $dateEnd->whereNull('date_end')
                        ->orWhere('date_end', '>=', date('Y-m-d H:i:s'));
                }
            );
    }
}
