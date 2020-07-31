<?php

namespace Socrates\Drivers;

use BotMan\Drivers\Slack\SlackDriver as BotManSlackDriver;

class SlackDriver extends BotManSlackDriver
{

    use BuildServicePayloadWithLinksTrait;

}