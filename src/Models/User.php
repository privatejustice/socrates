<?php

namespace Socrates\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function reminders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function aliases(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Alias::class);
    }

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->aliases()->delete();
        $this->reminders()->delete();

        return parent::delete();
    }

    public function groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function debts_to_pay(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Debt::class, 'from_id');
    }

    public function debts_to_receive(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Debt::class, 'to_id');
    }

    public function addToGroup($group): void
    {
        $this->groups()->sync([$group->id], false);
    }

    public static function findByUsernameOrFail($username)
    {
        $user = self::where('username', $username)->first();

        if (! $user) {
            throw new ModelNotFoundException('User not found.');
        }

        return $user;
    }

    /**
     * @param $botUser
     *
     * @return \Socrates\Models\User
     */
    public static function findOrCreateTelegram($botUser)
    {
        $user = self::where('telegram_id', $botUser->getId())->first();

        if (! $user) {
            $user = new self;
            $user->telegram_id = $botUser->getId();
            $user->name = $botUser->getFirstName() ?? $botUser->getId();
            $user->username = $botUser->getUsername();
            $user->email = $botUser->getId() . '@money-tracking.com';
            $user->password = Hash::make($botUser->getUsername() . '-money-tracking');
            $user->save();
        }

        return $user;
    }

    public function pays($amount): PaymentFactory
    {
        return new PaymentFactory($this, $amount);
    }
}
