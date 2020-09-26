<?php

namespace Socrates\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public array $errors = [];

    public function __construct(array $errors)
    {
        parent::__construct('The given data failed to pass validation.');

        $this->errors = $errors;
    }
}
