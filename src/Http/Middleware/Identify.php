<?php
namespace Socrates\Http\Middleware;

use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Users\User;
use BotMan\BotMan\BotMan;
use Api;

class Identify implements Received, Sending
{

    public function received(IncomingMessage $message, $next, BotMan $bot)
    {

        $driver = $bot->getDriver();
        $user = $bot->getDriver()->getUser($message);

        $this->identify($message, $driver, $user);

        return $next($message);

    }

    // Used to identifiy server originated messages
    public function sending($payload, $next, BotMan $bot)
    {

        // The server fakes an incoming message from the user
        // Use this to identify the recipient

        $message = $bot->getMessage();
        $driver = $bot->getDriver();
        $user = $bot->getDriver()->getUser($message);

        if($user->getId() == null) {
            $user = new User($message->getSender());
        }

        $this->identify($message, $driver, $user);

        return $next($payload);

    }

    function identify(IncomingMessage $message, \BotMan\BotMan\Interfaces\DriverInterface $driver, \BotMan\BotMan\Interfaces\UserInterface $user): void
    {

        $service = \Socrates\Chat\Driver::getServiceName($driver);
        $params = [
        'service' => $service,
        'user_id' => $user->getId()
        ];

        try {
            $chatUser = Api::render('ChatUser', 'getsingle', $params);
            $contactId = $chatUser['contact_id'];
        } catch (Exception $e) {

            if(defined(get_class($driver).'::KNOWS_CONTACT_ID') && $driver::KNOWS_CONTACT_ID) {

                $this->createUser($service, $user->getId(), $user->getId());
                $contactId = $user->getId();
            }else{

                $contactId = $this->createContact($user, $service);
                $this->createUser($service, $user->getId(), $contactId);
            }
        }

        $message->addExtras('contact_id', $contactId);

    }

    function createContact(\BotMan\BotMan\Interfaces\UserInterface $user, string $service)
    {
        $contact = Api::render(
            'Contact', 'create', [
            'contact_type' => 'Individual',
            'source' => 'Chatbot',
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName()
            ]
        );

        $result = Api::render(
            'EntityTag', 'create', array(
            'contact_id' => $contact['id'],
            'tag_id' => "Chatbot"
            )
        );

        $extraInfoClass = "addExtra{$service}Info";
        if(method_exists($this, $extraInfoClass)) {
            $this->$extraInfoClass($user, $contact['id']);
        }

        return $contact['id'];
    }

    function createUser(string $service, string $userId, string $contactId): void
    {
        $result = Api::render(
            'ChatUser', 'create', [
            'service' => $service,
            'user_id' => $userId,
            'contact_id' => $contactId
            ]
        );
    }

    function addExtraFacebookInfo($user, $contactId): void
    {

        $info = $user->getInfo();

        // Download photo from Facebook
        $imageName = md5($user->getId().$contactId) . '.jpg';
        $path = Civi::paths()->getPath(Civi::settings()->get('customFileUploadDir')) . $imageName;
        file_put_contents($path, file_get_contents($info['profile_pic']));

        Api::render(
            'Contact', 'create', [
            'id' => $contactId,
            'image_URL' => \Socrates\Utils_System::url('socrates/contact/imagefile', ['photo' => $imageName], true),
            'gender' => $info['gender']
            ]
        );

    }


    //     /**
    //      * Handle an incoming message.
    //      *
    //      * @param IncomingMessage $message
    //      * @param callable $next
    //      * @param BotMan $bot
    //      *
    //      * @return mixed
    //      */
    //     public function received(IncomingMessage $message, $next, BotMan $bot)
    //     {
    //         // $botUser = $bot->getDriver()->getUser($message);

    //         // $user = User::firstOrCreate(['telegram_id' => $botUser->getId()], [
    //         //     'name' => $botUser->getFirstName() ?? $botUser->getId(),
    //         //     'surname' => $botUser->getLastName(),
    //         //     'username' => $botUser->getUsername(),
    //         //     'email' => $botUser->getId() . '@socratesbot.com',
    //         //     'password' => Hash::make($botUser->getUsername() . '-socratesbot'),
    //         // ]);

    //         // auth()->login($user);
    // dd($bot->getDriver());
    //         if (is_null($bot->getDriver()->getUser($message)->getId())) {

    //             return $next($message);

    //         }

    //         $user = (new UserService())->findOrCreate($bot->getDriver()->getUser($message));

    //         auth()->login($user);

    //         $this->setUserLanguage($message);

    //         // $this->checkIsGroupConversation($message, $bot);

    //         $this->checkIsNotTalkingToBot($message, $bot);

    //         $this->loadUserInGroup($message, $bot);

    //         return $next($message);
    //     }

    //     private function setUserLanguage(IncomingMessage $message)
    //     {
    //         if (isset($message->getPayload()['from']['language_code'])) {
    //             app()->setLocale($message->getPayload()['from']['language_code']);
    //         }
    //     }

    //     /**
    //      * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
    //      * @param \BotMan\BotMan\BotMan $bot
    //      *
    //      * @throws \Socrates\Exceptions\PrivateConversationNotAllowedException
    //      * @throws \BotMan\BotMan\Exceptions\Base\BotManException
    //      */
    //     private function checkIsGroupConversation(IncomingMessage $message, BotMan $bot)
    //     {
    //         if ($message->getPayload()['chat']['type'] === 'group') {
    //             return;
    //         }

    //         $bot->say(trans('errors.bot_is_for_groups'), $message->getRecipient());

    //         throw new PrivateConversationNotAllowedException();
    //     }

    //     /**
    //      * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
    //      * @param \BotMan\BotMan\BotMan $bot
    //      *
    //      * @throws \Socrates\Exceptions\InteractingWithBotException
    //      * @throws \BotMan\BotMan\Exceptions\Base\BotManException
    //      */
    //     private function checkIsNotTalkingToBot(IncomingMessage $message, BotMan $bot)
    //     {
    //         if (strpos($message->getText(), '@'.config('botman.telegram.bot.username')) !== false) {

    //             $bot->say(trans('debts.you_cannot_debt_to_bot'), $message->getRecipient());

    //             throw new InteractingWithBotException();
    //         }
    //     }

    //     /**
    //      * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
    //      * @param \BotMan\BotMan\BotMan $bot
    //      *
    //      * @throws \Socrates\Exceptions\MissingGroupException
    //      * @throws \BotMan\BotMan\Exceptions\Base\BotManException
    //      */
    //     private function loadUserInGroup(IncomingMessage $message, BotMan $bot)
    //     {
    //         $user = User::findOrCreateTelegram($bot->getDriver()->getUser($message));

    //         auth()->login($user);

    //         $group = Group::where('telegram_id', collect($message->getPayload())->get('chat')['id'])->first();

    //         if ($group) {
    //             $user->addToGroup($group);

    //             $user->group = $group;

    //             app()->setLocale($group->language);
    //             return ;
    //         }

    //         return ; // @todo
        
    //         if (! $this->isRegisteringGroup($message, $bot)) {
    //             $bot->say(trans('groups.first_register'), $message->getRecipient());

    //             throw new MissingGroupException();
    //         }
    //     }

    //     private function isRegisteringGroup(IncomingMessage $message, BotMan $bot)
    //     {
    //         $payload = collect($message->getPayload());

    //         $conversation = $bot->getStoredConversation($message);

    //         return $payload->contains('new_chat_members')
    //             || $payload->contains('group_chat_created')
    //             || $message->getText() === '/register'
    //             || ($conversation && $conversation['conversation'] instanceof RegisterGroupConversation);
    //     }
}
