<?php

namespace Socrates\Models;

use Socrates\Traits\HasCurrency;

class Group extends Model
{

    use HasCurrency;

    const UPDATED_AT = null;

    /**
     * @var array
     */
    protected array $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public static function updateOrCreateFromChat($chat, string $language = 'es', string $currency = 'eur'): self
    {
        $group = self::updateOrCreate(
            [
            'telegram_id' => $chat['id'],
            'type' => $chat['type'],
            ], [
            'title' => $chat['title'],
            'language' => $language,
            'currency' => $currency,
            ]
        );

        return $group;
    }

}
