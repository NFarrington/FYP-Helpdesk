<?php

namespace App\Notifications\Concerns;

trait Configurable
{
    /**
     * The notification key.
     *
     * @var string
     */
    protected static $key = null;

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
