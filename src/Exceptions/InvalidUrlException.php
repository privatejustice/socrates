<?php

namespace Socrates\Exceptions;

use Exception;

class InvalidUrlException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function render($request): void
    {

    }
}
