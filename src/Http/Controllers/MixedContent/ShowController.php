<?php

namespace Socrates\Http\Controllers\MixedContent;

use Socrates\Http\Controllers\Controller;
use Socrates\Services\Socrates\MixedContent;
use Socrates\Services\Socrates\Services\Socrates;
use BotMan\BotMan\BotMan;

class ShowController extends Controller
{

    /**
     * @var \Socrates\Services\Socrates\Services\Socrates 
     */
    protected $dear;

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

        $mixedContent = $this->dear->getMixedContent($site->id);

        if ($mixedContent->isEmpty()) {
            $bot->reply(trans('socrates.mixedcontent.perfect'));
        } else {

            $mixedContent->each(
                function (MixedContent $mixed) use ($bot) {
                    $bot->reply(
                        trans(
                            'socrates.mixedcontent.result', [
                            'url' => $mixed->mixedContentUrl,
                            'origin' => $mixed->foundOnUrl,
                            ]
                        ), ['disable_web_page_preview' => true]
                    );
                }
            );
        }

        $bot->reply($site->getKeyboard());
    }
}
