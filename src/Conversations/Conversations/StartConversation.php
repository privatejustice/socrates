<?php

namespace Socrates\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class StartConversation extends Conversation
{
    /**
     * @return void
     */
    public function run()
    {
        $this->bot->reply(trans('socrates.greetings'));

        if (! auth()->user()->token) {
            $this->askToken();
        } else {
            $this->bot->reply(trans('socrates.already_set_up'));
        }
    }

    public function askToken(): void
    {
        $this->ask(
            trans('socrates.token.question'), function (Answer $answer) {

                auth()->user()->token = encrypt($answer->getText());
                auth()->user()->save();

                $this->bot->reply(trans('socrates.token.stored'));

                $this->askWebhook();
            }
        );
    }

    public function askWebhook(): void
    {
        $this->ask(
            trans('socrates.webhook.question'), function (Answer $answer) {

                auth()->user()->webhook = encrypt($answer->getText());
                auth()->user()->save();

                $this->bot->reply(
                    trans('socrates.webhook.stored', ['url' => auth()->user()->getWebhookUrl()]),
                    ['parse_mode' => 'Markdown']
                );
            }, ['parse_mode' => 'Markdown']
        );
    }

}