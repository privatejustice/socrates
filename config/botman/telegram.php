<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Telegram Token
    |--------------------------------------------------------------------------
    |
    | Your Telegram bot token you received after creating
    | the chatbot through Telegram.
    |
    */
    'token' => env('SERVICE_TELEGRAM_TOKEN'),

    'bot' => [
        'id' => env('SERVICE_TELEGRAM_BOT_ID'),
        'username' => env('SERVICE_TELEGRAM_BOT_USERNAME')
    ],
];
