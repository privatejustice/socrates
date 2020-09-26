<?php

namespace Socrates\Resources;

class Check extends ApiResource
{
    /**
     * The id of the site.
     *
     * @var int
     */
    public $id;

    /**
     * The type of the check.
     *
     * @var string
     */
    public $type;

    /**
     * The human readable version of type.
     *
     * @var string
     */
    public $label;

    /**
     * Is the check enabled?
     *
     * @var bool
     */
    public $enabled;
}
