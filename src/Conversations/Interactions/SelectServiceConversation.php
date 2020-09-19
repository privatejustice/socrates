<?php

namespace Socrates\Conversations\Interactions;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SelectServiceConversation extends Conversation
{
    public function askService()
    {
        $question = Question::create('What kind of Service you are looking for?')
            ->callbackId('select_service')
            ->addButtons(
                [
                Button::create('Hair')->value('Hair'),
                Button::create('Spa')->value('Spa'),
                Button::create('Beauty')->value('Beauty'),
                ]
            );

        $this->ask(
            $question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    $this->bot->userStorage()->save(
                        [
                        'service' => $answer->getValue(),
                        ]
                    );
                }
            }
        );
    }

    public function run()
    {
        $this->askService();
    }
}
