<?php

namespace Socrates\Conversations;

use Socrates\Services\Socrates\Services\Socrates;
use Socrates\Services\Socrates\Site;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class SiteDestroyConversation extends Conversation
{
    /**
     * @var \Socrates\Services\Socrates\Site  
     */
    private $site;

    /**
     * @var \Socrates\Services\Socrates\Services\Socrates  
     */
    private $dear;


    public function __construct(Socrates $dear, Site $site)
    {
        $this->dear = $dear;
        $this->site = $site;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->askFirstConfirmation();
    }

    public function askFirstConfirmation(): void
    {
        $this->ask(
            $this->getQuestion(trans('socrates.sites.delete_confirm_1')), function (Answer $answer) {

                $nextStep = $answer->isInteractiveMessageReply()
                ? $answer->getValue()
                : $this->answerToBoolean($answer->getText());

                if (! $nextStep) {
                    $this->bot->reply(trans('socrates.sites.delete_cancel'));

                    return;
                }

                $this->askSecondConfirmation();

            }
        );
    }

    private function getQuestion(string $message): Question
    {
        return Question::create($message)
            ->fallback('Unable to delete the site, please try again later.')
            ->addButtons(
                [
                Button::create('Yes')->value(true),
                Button::create('No')->value(false),
                ]
            );
    }

    public function askSecondConfirmation(): void
    {
        $this->ask(
            $this->getQuestion(trans('socrates.sites.delete_confirm_2')), function (Answer $answer) {

                $nextStep = $answer->isInteractiveMessageReply()
                ? $answer->getValue()
                : $this->answerToBoolean($answer->getText());

                if (! $nextStep) {
                    $this->bot->reply(trans('socrates.sites.delete_cancel'));

                    return;
                }

                $this->site->delete();

                $this->bot->reply(trans('socrates.sites.deleted'));
            }
        );
    }

    public function answerToBoolean(string $answer): bool
    {
        $positiveMessages = ['true', 'yes', 'of course', 'yeah', 'affirmative', 'i confirm'];

        return in_array(strtolower($answer), $positiveMessages);
    }

}