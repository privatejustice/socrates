<?php

namespace Socrates\Resources;

class Uptime extends ApiResource
{
    /** @var string */
    public $datetime;

    /** @var float */
    public $uptimePercentage;
}
