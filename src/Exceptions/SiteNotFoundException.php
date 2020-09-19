<?php

namespace Socrates\Exceptions;

use Exception;

class SiteNotFoundException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        app('botman')->reply(trans('socrates.sites.not_found'));

        return response('Site not found');
    }
}
