<?php

namespace Socrates\Jobs\Webhook;

use Socrates\Models\User;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UptimeCheckFailed implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload;

    /**
     * @var \BotMan\BotMan\BotMan 
     */
    public $botman;

    /**
     * @var \Socrates\Models\User 
     */
    public $user;

    public function __construct($payload, User $user)
    {
        $this->payload = $payload;
        $this->user = $user;
        $this->botman = resolve('botman');
    }

    public function handle()
    {
        $this->botman->say(
            trans('socrates.webhook.uptime_check_failed', ['url' => $this->payload->site->url]),
            $this->user->telegram_id,
            TelegramDriver::class
        );
    }
}
