<?php

namespace Socrates\Jobs\Webhook;

use Socrates\Models\User;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BrokenLinksFound implements ShouldQueue
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

    public function handle(): void
    {
        $this->botman->say(
            trans('socrates.webhook.broken_links_found', ['url' => $this->payload->site->url]),
            $this->user->telegram_id,
            TelegramDriver::class,
            ['disable_web_page_preview' => true]
        );

        foreach ($this->payload->run->result_payload->broken_links as $brokenLink) {
            $this->reportBrokenLink($brokenLink);
        }
    }

    private function reportBrokenLink($link): void
    {
        $this->botman->say(
            trans(
                'socrates.brokenlinks.result', [
                'url' => $link->crawled_url,
                'code' => $link->status_code,
                'origin' => $link->found_on_url,
                ]
            ),
            $this->user->telegram_id,
            TelegramDriver::class,
            ['disable_web_page_preview' => true]
        );
    }
}
