<?php


namespace Socrates\Http\Controllers\Webhook;


use Socrates\Http\Controllers\Controller;
use BotMan\BotMan\BotMan;

class StoreController extends Controller
{

    public function __invoke(BotMan $bot, string $secret)
    {
        auth()->user()->webhook = encrypt($secret);
        auth()->user()->save();

        $bot->reply(
            trans('socrates.webhook.stored', ['url' => auth()->user()->getWebhookUrl()]),
            ['parse_mode' => 'Markdown']
        );
    }
}