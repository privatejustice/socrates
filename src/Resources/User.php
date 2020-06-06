<?php

namespace Socrates\Resources;

class User extends ApiResource
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    /** @var string */
    public $photoUrl;

    /** @var Team[] */
    public $teams;

    public function __construct(array $attributes, $socrates = null)
    {
        parent::__construct($attributes, $socrates);

        $this->teams = array_map(function (array $teamAttributes) {
            return new Team($teamAttributes);
        }, $this->teams);
    }
}
