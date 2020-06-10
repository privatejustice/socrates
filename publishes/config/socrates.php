<?php

return [

    /*
     * A valid API token for your Oh Dear! account. Instructions on how to get a
     * token can be found on this page: https://socrates.app/docs/api/authentication
     */
    'api_token' => env('SOCRATES_API_TOKEN', ''),

    /*
     * The url of your site as shown in Oh Dear.
     * It should start with either 'http' or 'https'.
     */
    'site_url' => env('SOCRATES_SITE_URL', ''),
];
