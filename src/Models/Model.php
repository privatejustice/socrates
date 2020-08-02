<?php

namespace Socrates\Models;

use Illuminate\Database\Eloquent\Model as ModelBase;

class Model extends ModelBase
{
    public static function getsingle($data)
    {
        return static::firstOrCreate($data);
    }
}
