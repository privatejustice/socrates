<?php

namespace Socrates\Conversations\Interactions;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class StartConversation extends Conversation
{
    /**
     * @return void
     */
    public function run()
    {
        $this->bot->reply(trans('boravel.greetings'));

        if (! auth()->user()->token) {
            $this->askToken();
        } else {
            $this->bot->reply(trans('boravel.already_set_up'));
        }
    }

    public function askToken(): void
    {
        $this->ask(
            trans('boravel.token.question'), function (Answer $answer) {

                auth()->user()->token = encrypt($answer->getText());
                auth()->user()->save();

                $this->bot->reply(trans('boravel.token.stored'));

                $this->askWebhook();
            }
        );
    }

    public function askWebhook(): void
    {
        $this->ask(
            trans('boravel.webhook.question'), function (Answer $answer) {

                auth()->user()->webhook = encrypt($answer->getText());
                auth()->user()->save();

                $this->bot->reply(
                    trans('boravel.webhook.stored', ['url' => auth()->user()->getWebhookUrl()]),
                    ['parse_mode' => 'Markdown']
                );
            }, ['parse_mode' => 'Markdown']
        );
    }

}