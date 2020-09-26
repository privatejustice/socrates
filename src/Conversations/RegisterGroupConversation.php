<?php

namespace Socrates\Conversations;

use Socrates\Models\Group;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Socrates\Traits\HasCurrency;

class RegisterGroupConversation extends Conversation
{

    use HasCurrency;

    /**
     * @var string 
     */
    protected $language;

    /**
     * @var string 
     */
    protected $currency;

    /**
     * Start the conversation
     *
     * @return void
     */
    public function run()
    {
        $this->askLanguage();
    }

    public function askLanguage(): self
    {
        $this->language = $this->bot->getUser()->getInfo()['language_code'] ?? app()->getLocale();

        app()->setLocale($this->language);

        $this->say(trans('groups.new_group_greetings'));

        return $this->ask(
            $this->getQuestionLanguage(), function (Answer $answer) {
                if (! $answer->isInteractiveMessageReply()) {
                    return;
                }

                $this->language = $answer->getValue();

                return $this->askCurrency();
            }
        );
    }

    public function askCurrency(): self
    {
        app()->setLocale($this->language);

        return $this->ask(
            $this->getQuestionCurrency(), function (Answer $answer) {
                if (! $answer->isInteractiveMessageReply()) {
                    return;
                }

                app()->setLocale($this->language);

                $this->currency = $answer->getValue();

                $this->say(trans('groups.new_group_created'));

                Group::updateOrCreateFromChat(
                    collect($this->bot->getMessage()->getPayload())->get('chat'),
                    $this->language,
                    $this->currency
                );
            }
        );
    }

    protected function getQuestionLanguage(): Question
    {
        return Question::create(trans('groups.ask_language'))
            ->addButtons(
                [
                Button::create('Català')->value('ca'),
                Button::create('Castellano')->value('es'),
                Button::create('English')->value('en'),
                ]
            );
    }

    protected function getQuestionCurrency(): Question
    {
        return Question::create(trans('groups.ask_currency'))
            ->addButtons($this->getCurrenciesAsButtons());
    }

    /**
     * @return Button[]
     *
     * @psalm-return list<Button>
     */
    protected function getCurrenciesAsButtons(): array
    {
        return array_map(
            function ($symbol, $currency) {
                return Button::create($symbol)->value($currency);
            }, $this->currency_symbols, array_keys($this->currency_symbols)
        );
    }
}
