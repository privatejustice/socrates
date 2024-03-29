<?php

namespace Socrates\Conversations;

use Socrates\Models\Reminder;
use Socrates\Services\ReminderService;
use Socrates\Services\StationService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class CreateReminderConversation extends Conversation
{

    const REMINDER_UNKNOWN = 'No sé quina acció és aquesta.';

    const CANT_UNDERSTAND = 'Ho sento, però no t\'he entès';

    /**
     * @var \Socrates\Services\ReminderService  
     */
    protected $reminderService;

    /**
     * @var \Socrates\Services\StationService  
     */
    protected $stationService;

    /**
     * @var string 
     */
    protected $reminderType;

    /**
     * @var \Socrates\Conversations\Girocleta\Station 
     */
    protected $reminderStation;

    /**
     * @var \Illuminate\Support\Carbon 
     */
    protected $reminderTime;

    /**
     * @var \Illuminate\Support\Collection 
     */
    protected $reminderDays;

    public function __construct()
    {
        $this->reminderService = new ReminderService();
        $this->stationService = app(StationService::class);
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $question = Question::create('Què vols que et recordi?')->addButtons($this->reminderService->asButtons());

        return $this->ask(
            $question, function (Answer $answer) {
                $this->reminderType = $answer->getValue();

                if (! $this->reminderService->find($this->reminderType)) {
                    return $this->say(self::REMINDER_UNKNOWN);
                }

                return $this->askStation();
            }
        );
    }

    /**
     * @return static
     */
    public function askStation(): self
    {
        $question = Question::create('De quina estació voldràs la informació?')->addButtons($this->stationService->asButtons());

        return $this->ask(
            $question, function (Answer $answer) {

                $this->reminderStation = $this->stationService->find($answer->getValue());

                if (! $this->reminderStation) {
                    return $this->say('No sé quina estació és');
                }

                return $this->askTime();
            }
        );
    }

    /**
     * @return static
     */
    public function askType(): self
    {

        $question = Question::create('Què vols que et recordi?')->addButtons($this->reminderService->asButtons());

        return $this->ask(
            $question, function (Answer $answer) {
                $this->reminderType = $answer->getValue();

                if (! $this->reminderService->find($this->reminderType)) {
                    return $this->say(self::REMINDER_UNKNOWN);
                }

                return $this->askTime();
            }
        );
    }

    /**
     * @return static
     */
    public function askTime(): self
    {
        return $this->ask(
            'A quina hora vols que t\'ho recordi?', function (Answer $answer) {

                $this->reminderTime = $this->reminderService->parseHoursFromInput($answer->getText());

                if (! $this->reminderTime) {
                    return $this->say("No he entès la hora a la que vols que t'ho recordi, prova a escriure-ho així: ".date('H:i'));
                }

                return $this->askDays();
            }
        );
    }

    /**
     * @return static
     */
    public function askDays(): self
    {
        $question = Question::create('Quins dies vols que t\'ho recordi? Si vols dies saltejats, escriu-los en comptes de triar un botó')
            ->addButtons($this->reminderService->possibleDaysButtons());

        return $this->ask(
            $question, function (Answer $answer) {

                $answerValue = $answer->isInteractiveMessageReply() ? $answer->getValue() : $answer->getText();

                $this->reminderDays = $this->reminderService->parseDaysFromInput($answerValue);

                if (! $this->reminderDays->count()) {
                    return $this->say('Em sap greu, però no he entès quins dies vols que t\'ho recordi');
                }

                $reminder = $this->createReminder();

                $this->say('Molt bé! Això era tot el que necessitàvem, aquest és el teu nou recordatori:');

                return $this->say("Recorda'm {$reminder->type_str} el {$reminder->days_str} a les {$reminder->time}");
            }
        );
    }

    public function createReminder(): Reminder
    {
        $reminder = new Reminder();
        $reminder->user_id = auth()->user()->id;
        $reminder->station_id = $this->reminderStation->id;
        $reminder->type = $this->reminderType;
        $reminder->time = $this->reminderTime;

        $reminder->setDays($this->reminderDays);

        $reminder->save();

        return $reminder;
    }
}
