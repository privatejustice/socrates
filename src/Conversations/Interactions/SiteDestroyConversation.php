<?php

namespace Socrates\Conversations\Interactions;

use Boravel\Services\Socrates\Services\Socrates;
use Boravel\Services\Socrates\Site;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class SiteDestroyConversation extends Conversation
{
    /**
     * @var \Boravel\Services\Socrates\Site  
     */
    private $site;

    /**
     * @var \Boravel\Services\Socrates\Services\Socrates  
     */
    private $dear;


    public function __construct(Boravel $dear, Site $site)
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
            $this->getQuestion(trans('boravel.sites.delete_confirm_1')), function (Answer $answer) {

                $nextStep = $answer->isInteractiveMessageReply()
                ? $answer->getValue()
                : $this->answerToBoolean($answer->getText());

                if (! $nextStep) {
                    $this->bot->reply(trans('boravel.sites.delete_cancel'));

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
            $this->getQuestion(trans('boravel.sites.delete_confirm_2')), function (Answer $answer) {

                $nextStep = $answer->isInteractiveMessageReply()
                ? $answer->getValue()
                : $this->answerToBoolean($answer->getText());

                if (! $nextStep) {
                    $this->bot->reply(trans('boravel.sites.delete_cancel'));

                    return;
                }

                $this->site->delete();

                $this->bot->reply(trans('boravel.sites.deleted'));
            }
        );
    }

    public function answerToBoolean(string $answer): bool
    {
        $positiveMessages = ['true', 'yes', 'of course', 'yeah', 'affirmative', 'i confirm'];

        return in_array(strtolower($answer), $positiveMessages);
    }

}