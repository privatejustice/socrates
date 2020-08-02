<?php

namespace Socrates\Models;

use Socrates\Services\StationService;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class Contact extends Model
{
    protected $table = 'chat_contacts';

    protected $fillable = [
     'user_id', 
     'service', 
    ];

    public function users()
    {
        return $this->hasMany(ChatUser::class, 'contact_id', 'id');
    }

    /**', [
        'id' => $bot->getMessage()->getExtras('contact_id'),
        'source_contact_id' => $bot->getMessage()->getExtras('contact_id'),
        'service' => \Socrates\Chat\Driver::getServiceName($bot->getDriver()),
        'conversation_type_id' => \Socrates\Bao\ChatConversationType::findById($hear->chat_conversation_type_id)->id
      ]); */
    public static function start_conversation()
    {

    }
}
