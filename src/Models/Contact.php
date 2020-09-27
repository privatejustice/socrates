<?php

namespace Socrates\Models;

use Socrates\Services\StationService;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class Contact extends Model
{
    protected $table = 'chat_contacts';

    /**
     * @var string[]
     *
     * @psalm-var array{0: string, 1: string}
     */
    protected $fillable = [
     'user_id', 
     'service', 
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasMany<ChatUser>
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChatUser::class, 'contact_id', 'id');
    }
}
