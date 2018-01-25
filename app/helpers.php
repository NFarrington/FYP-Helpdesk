<?php

if (!function_exists('app_key')) {
    /**
     * Retrieve the application key from the configuration.
     *
     * @return string
     */
    function app_key()
    {
        $key = app()['config']['app.key'];
        if (starts_with($key, 'base64:')) {
            $key = base64_decode(mb_substr($key, 7));
        }

        return $key;
    }
}
