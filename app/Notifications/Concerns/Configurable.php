<?php

namespace App\Notifications\Concerns;

trait Configurable
{
    /**
     * Get the notification key.
     *
     * @return string
     */
    public static function getKey()
    {
        return self::$key;
    }
}
