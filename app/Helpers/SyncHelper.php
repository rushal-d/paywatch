<?php

namespace App\Helpers;

class SyncHelper
{
    const sync = 1;
    const unSync = 0;

    public static function getSync()
    {
        return static::sync;
    }

    public static function getUnSync()
    {
        return static::unSync;
    }

}
