<?php

namespace Socrates\Http\Controllers\BrokenLinks;

use Socrates\Http\Controllers\Controller;
use Socrates\Services\Socrates\BrokenLink;
use Socrates\Services\Socrates\Services\Socrates;
use BotMan\BotMan\BotMan;

class ShowController extends Controller
{


    /**
     * @var \Socrates\Services\Socrates\Services\Socrates 
     */
    protected $dear;

    public function __construct(Socrates $dear)
    {
        $this->dear = $dear;
    }

    /**
     * Handle the incoming request.
     *
     * @param \BotMan\BotMan\BotMan $bot
     * @param string                $url
     *
     * @return void
     * @throws \Socrates\Exceptions\SiteNotFoundException
     */
    public function __invoke(BotMan $bot, string $url)
    {
        $bot->types();

        $site = $this->dear->findSite($url);

        $links = $this->dear->getBrokenLinks($site->id);

        if ($links->isEmpty()) {
            $bot->reply(trans('socrates.brokenlinks.perfect'));
            
        } else {

            $links->each(
                function (BrokenLink $link) use ($bot) {
                    $bot->reply(
                        trans(
                            'socrates.brokenlinks.result', [
                            'url' => $link->crawledUrl,
                            'code' => $link->statusCode,
                            'origin' => $link->foundOnUrl
                            ]
                        ), ['disable_web_page_preview' => true]
                    );
                }
            );
        }

        $bot->reply($site->getKeyboard());
    }
}
