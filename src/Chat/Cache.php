<?php
namespace Socrates\Chat;

use BotMan\BotMan\Interfaces\CacheInterface;

class Cache implements CacheInterface
{

    public function has($key)
    {
        $entry = new \Socrates\Bao\ChatCache;
        $entry->identifier = $key;
        return (bool) $entry->find();
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->prune();
        $entry = new \Socrates\Bao\ChatCache;
        $entry->identifier = $key;
        if($entry->find()) {
            $entry->fetch();
            return unserialize($entry->value);
        }
        return $default;
    }

    /**
     * Retrieve an item from the cache and delete it.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        $this->prune();
        $entry = new \Socrates\Bao\ChatCache;
        $entry->identifier = $key;
        if($entry->find()) {
            $entry->fetch();
            $value = $entry->value;
            $entry->delete();
            return unserialize($value);
        }
        return $default;

    }

    /**
     * Store an item in the cache.
     *
     * @param  string        $key
     * @param  mixed         $value
     * @param  \DateTime|int $minutes
     * @return void
     */
    public function put($key, $value, $minutes)
    {
        if(!(is_integer($minutes) || $minutes < 1)) {
            return;
        }
        $entry = new \Socrates\Bao\ChatCache;
        $entry->identifier = $key;
        if($entry->find()) {
            $entry->fetch();
            $entry->expires = date_format(new DateTime("+{$minutes} minutes"), 'Y-m-d H:i:s');
            $entry->value = serialize($value);
            $entry->update();
        }else{
            $entry->expires = date_format(new DateTime("+{$minutes} minutes"), 'Y-m-d H:i:s');
            $entry->value = serialize($value);
            $entry->insert();
        }
    }

    public function prune(): void
    {
        $expired = new \Socrates\Bao\ChatCache;
        $expired->whereAdd('expires < NOW()');
        $expired->delete(DB_DATAOBJECT_WHEREADD_ONLY);
    }
}
