<?php

namespace Socrates\Conversations\Interactions;

use Illuminate\Foundation\Inspiring;

class ExampleConversation
{
    /**
     * First question
     */
    public function askReason()
    {
        $question = Question::create("Huh - you woke me up. What do you need?")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons(
                [
                Button::create('Tell a joke')->value('joke'),
                Button::create('Give me a fancy quote')->value('quote'),
                ]
            );

        return $this->ask(
            $question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    if ($answer->getValue() === 'joke') {
                        $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                        $this->say($joke->value->joke);
                    } else {
                        $this->say(Inspiring::quote());
                    }
                }
            }
        );
    }

    /**
     * Start the conversation
     *
     * @return void
     */
    public function run(): void
    {
        $this->askReason();
    }
}
