<?php

namespace Socrates\Http\Controllers\Sites;

use Socrates\Http\Controllers\Controller;
use Socrates\Services\Socrates\Services\Socrates;
use Socrates\Services\Socrates\Site;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class IndexController extends Controller
{

    /** @var \Socrates\Services\Socrates\Services\Socrates */
    protected $dear;

    public function __construct(Socrates $dear)
    {
        $this->dear = $dear;
    }

    /**
     * Handle the incoming request.
     *
     * @param \BotMan\BotMan\BotMan $bot
     *
     * @return void
     */
    public function __invoke(BotMan $bot)
    {
        $bot->types();

        $sites = $this->dear->sites();

        if (! $sites->count()) {
            $bot->reply(trans('socrates.sites.list_empty'));

            return;
        }

        $buttons = $sites->map(function (Site $site) {
            return Button::create($site->getResume())->value("/site {$site->id}");
        })->toArray();

        $message = (new Question(trans('socrates.sites.list_message')))->addButtons($buttons);

        $bot->reply($message);
    }
}
