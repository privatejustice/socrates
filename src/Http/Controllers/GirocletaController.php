<?php

namespace Socrates\Http\Controllers;

use Socrates\Conversations\RegisterConversation;
use Socrates\Conversations\Girocleta\Station;
use Socrates\Outgoing\OutgoingMessage;
use Socrates\Services\StationService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Location;

class GirocletaController extends Controller
{

    /**
     * @var \Socrates\Services\StationService 
     */
    protected $stationService;

    public function __construct()
    {
        $this->stationService = app(StationService::class);
    }

    /**
     * Shows current station information.
     *
     * @param \BotMan\BotMan\BotMan $bot
     *
     * @return mixed
     */
    public function greetings(BotMan $bot)
    {
        $station = $this->stationService->find(auth()->user()->station_id);

        if ($station === null) {
            return $this->registerConversation($bot);
        }

        $bot->reply("Hola " . auth()->user()->name . "! 👋");
        $bot->reply("Aquí tens la informació de la teva estació 👇");
        $bot->reply($station->getVenueMessage(), $station->getVenuePayload());

    }

    /**
     * Start a conversation to register the user's main station.
     *
     * @param BotMan $bot
     */
    public function registerConversation(BotMan $bot)
    {
        return $bot->startConversation(new RegisterConversation());
    }

    /**
     * Show information about two stations and their distance.
     *
     * @param \BotMan\BotMan\BotMan $bot
     * @param string                $begin
     * @param string                $end
     *
     * @return mixed
     */
    public function tripInformation(BotMan $bot, $begin, $end)
    {
        if (! $beginStation = $this->stationService->findByText($begin)) {
            return $bot->reply("No he trobat cap estació semblant a '{$begin}'");
        }

        if (! $endStation = $this->stationService->findByText($end)) {
            return $bot->reply("No he trobat cap estació semblant a '{$end}'");
        }

        if ($beginStation->id === $endStation->id) {
            return $bot->reply("Estàs allà mateix ¯\_(ツ)_/¯");
        }

        $distance = $endStation->location->getDistance(
            $beginStation->location->latitude,
            $beginStation->location->longitude
        );

        if ($beginStation->wasFoundBy('address')) {
            $bot->reply("L'estació més propera a \"{$begin}\" és {$beginStation->name}");
        }

        if ($endStation->wasFoundBy('address')) {
            $bot->reply("L'estació més propera a \"{$end}\" és {$endStation->name}");
        }

        $bot->reply("Distancia aprox: {$distance}km");
        $bot->reply($beginStation->getVenueMessage(), $beginStation->getVenuePayload());
        $bot->reply($endStation->getVenueMessage(), $endStation->getVenuePayload());
    }


    /**
     * Show the nearest locations to the user.
     *
     * @param \BotMan\BotMan\BotMan                        $bot
     * @param \BotMan\BotMan\Messages\Attachments\Location $location
     *
     * @return void
     */
    public function nearStations(BotMan $bot, Location $location): void
    {
        $nearStations = $this->stationService->getNearStations($location->getLatitude(), $location->getLongitude());

        $bot->reply("Aquestes són les {$nearStations->count()} estacions que tens més a prop");

        $nearStations->each(
            function (Station $station) use ($bot) {

                $bot->reply($station->getVenueMessage(), $station->getVenuePayload());
            }
        );
    }

}
