<?php
use Socrates\Http\Controllers\BotManController;

$botman = resolve('botman');

// $botman->hears('Hi', function ($bot) {
//     $bot->reply('Hello!');
// });
// $botman->hears('Start conversation', BotManController::class.'@startConversation');

$botman->middleware->received(new \Socrates\Http\Middleware\Identify());
$botman->middleware->received(new \Socrates\Http\Middleware\RecordIncoming());
$botman->middleware->sending(new \Socrates\Http\Middleware\RecordOutgoing());

$botman->hears('hello|/hi|Hola|Oi|olá|👋', function ($bot) {
    $bot->reply('Hola! 👋');
});

$botman->hears('/help|^ajuda$|(?:no se )?(?:que fer|com (?:funciona|va))', 'Socrates\Http\Controllers\HelpController@index');

$botman->hears('/station|^estaci[ó|o]$|^hola$|👋', 'Socrates\Http\Controllers\GirocletaController@greetings');
$botman->hears('/start|(?:afegir|definir|canviar?) estaci[ó|o]', 'Socrates\Http\Controllers\GirocletaController@registerConversation');
$botman->hears('(?:(?:vull)? anar de |de )?(.*) a (.*)', 'Socrates\Http\Controllers\GirocletaController@tripInformation');
$botman->receivesLocation('Socrates\Http\Controllers\GirocletaController@nearStations');

$botman->hears('^/reminders$|els meus recordatoris|recordatoris', 'Socrates\Http\Controllers\RemindersController@index');
$botman->hears('^/reminder$|(?:afegir|definir|crear) recordatori', 'Socrates\Http\Controllers\RemindersController@create');
$botman->hears('^/reminderdelete$|(?:esborrar?|treu[re]?|oblidar?) recordatori', 'Socrates\Http\Controllers\RemindersController@destroy');

$botman->hears('/aliases|els meus alias|veure alias', 'Socrates\Http\Controllers\AliasesController@index');
$botman->hears('/alias$|(?:afegir|definir|crear?) alias', 'Socrates\Http\Controllers\AliasesController@create');
$botman->hears('^/aliasdelete|(?:esborrar?|treu[re]?|oblidar?) alias', 'Socrates\Http\Controllers\AliasesController@destroy');

$botman->hears('/remove$|/forget$|/delete$|(?:borrar?|oblidar?) usuari', 'Socrates\Http\Controllers\UsersController@destroy');

$botman->fallback('Socrates\Http\Controllers\FallbackController@index');

$botman->hears('/help', \Socrates\Http\Controllers\Help\ShowController::class);

$botman->hears('/start', \Socrates\Http\Controllers\Users\StoreController::class);
$botman->hears('/token {token}', \Socrates\Http\Controllers\Token\StoreController::class);


$botman->hears('/sites', \Socrates\Http\Controllers\Sites\IndexController::class);
$botman->hears('/newsite (.*[^\s])', \Socrates\Http\Controllers\Sites\StoreController::class);
$botman->hears('/site (.*[^\s])', \Socrates\Http\Controllers\Sites\ShowController::class);
$botman->hears('/deletesite (.*[^\s])', \Socrates\Http\Controllers\Sites\DestroyController::class);
$botman->hears('/downtime (.*[^\s])', \Socrates\Http\Controllers\Downtime\ShowController::class);
$botman->hears('/uptime (.*[^\s])', \Socrates\Http\Controllers\Uptime\ShowController::class);
$botman->hears('/brokenlinks (.*[^\s])', \Socrates\Http\Controllers\BrokenLinks\ShowController::class);
$botman->hears('/mixedcontent (.*[^\s])', \Socrates\Http\Controllers\MixedContent\ShowController::class);
$botman->hears('/webhook (.*)', \Socrates\Http\Controllers\Webhook\StoreController::class);


/**
 * Payments
 */


$botman->hears(
    '(?|'.implode('|', [
        'en' => 'I owe ([0-9]+) to @([^\s]+)',
        'ca' => '(?:(?:li dec)|(?:dec)) ([0-9]+) a @([^\s]+)',
        'es' => '(?:(?:le debo)|(?:debo)) ([0-9]+) a @([^\s]+)',
    ]).')',
    'Socrates\Http\Controllers\DebtsController@createFromMe'
);

$botman->hears(
    '(?|'.implode('|', [
        'en' => '@([^\s]+) owes me ([0-9]+)',
        'ca' => '@([^\s]+) (?:(?:em deu)|(?:hem deu)|(?:deu)) ([0-9]+)',
        'es' => '@([^\s]+) (?:(?:me debe)|(?:debe)) ([0-9]+)',
    ]).')',
    'Socrates\Http\Controllers\DebtsController@createFromOthers'
);


$botman->hears(
    '(?|'.implode('|', [
        'en' => 'I paid ([0-9]+) to @([^\s]+)',
        'ca' => '(?:(?:li he pagat)|(?:he pagat)|(?:pago)) ([0-9]+) a @([^\s]+)',
        'es' => '(?:(?:le he pagado)|(?:le pago)|(?:he pagado)) ([0-9]+) a @([^\s]+)',
    ]).')',
    'Socrates\Http\Controllers\PaymentsController@createFromMe'
);

$botman->hears(
    implode('|', [
        'en' => '@([^\s]+) paid me ([0-9]+)',
        'ca' => '@([^\s]+) (?:(?:m\'ha pagat)|(?:ha pagat)|(?:em paga)|(?:paga)) ([0-9]+)',
        'es' => '@([^\s]+) (?:(?:me ha pagado)|(?:ha pagado)|(?:me paga)) ([0-9]+)'
    ]),
    'Socrates\Http\Controllers\PaymentsController@createFromOthers'
);

$botman->hears('/debt ([0-9]+) @([^\s]+)', function ($bot, $amount, $username) {
    return app(Socrates\Http\Controllers\DebtsController::class)->createFromMe($bot, $amount, $username);
});
$botman->hears('/paid ([0-9]+) @([^\s]+)', function ($bot, $amount, $username) {
    return app(Socrates\Http\Controllers\DebtsController::class)->createFromOthers($bot, $username, $amount);
});

$botman->hears('/balance|/resum|/resumen|💰|💵', 'Socrates\Http\Controllers\DebtsController@index');

$botman->hears('/register', 'Socrates\Http\Controllers\GroupsController@register');
$botman->on('group_chat_created', 'Socrates\Http\Controllers\GroupsController@registerNewGroup');
$botman->on('new_chat_members', 'Socrates\Http\Controllers\GroupsController@registerNewChatMember');