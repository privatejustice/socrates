<?php

namespace Socrates\Conversations\Psicologo;

class IntroductionConversation extends Conversation
{
    protected $needHelp = false;

    public function askIfNeedHelp()
    {
        $this->ask('Você está bem? Pre', function (Answer $response) {
            $this->needHelp = true;
            $this->say('Cool - you said ' . $response->getText());
        });
    }

    public function run()
    {
        // This will be called immediately
        $this->askIfNeedHelp();
    }
}