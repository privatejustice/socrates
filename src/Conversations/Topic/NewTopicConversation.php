<?php

namespace Socrates\Conversations\Topic;

class NewTopicConversation extends Conversation
{
    protected $firstname;

    protected $email;

    protected $phone;

    public function askFirstname()
    {
        $this->ask('Hello! What is your firstname?', function(Answer $answer) {
            // Save result
            $this->firstname = $answer->getText();

            $this->say('Nice to meet you '.$this->firstname);
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('One more thing - what is your email?', function(Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->say('Great - that is all we need, '.$this->firstname);
        });
    }

    public function askIfNeedHelp()
    {
        $this->ask('Como foi seu dia ?', function (Answer $response) {
            $this->say('Interessante que você fez ' . $response->getText());
        });
    }

    public function run()
    {
        // This will be called immediately
        $this->askFirstname();
    }
}