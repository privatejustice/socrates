<?php

namespace Socrates\Exceptions;

use Exception;

class MissingGroupException extends Exception
{
    public function __construct()
    {
        parent::__construct('Faltando Grupo.');
    }
}
