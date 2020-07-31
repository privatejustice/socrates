<?php

namespace Socrates\Exceptions;

use Exception;

class PrivateConversationNotAllowedException extends Exception
{
    public function __construct()
    {
        parent::__construct('The resource you are looking for could not be found.');
    }
}
