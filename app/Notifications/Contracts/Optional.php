<?php

namespace App\Notifications\Contracts;

interface Optional
{
    /**
     * Get the notification key.
     *
     * @return string
     */
    public static function getKey();
}
