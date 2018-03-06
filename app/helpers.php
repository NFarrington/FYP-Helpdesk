<?php

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

/**
 * Convert markdown text to HTML.
 *
 * @param $text
 * @return null|string|string[]
 * @throws Exception
 */
function markdown($text)
{
    return (new ParsedownExtra)->text($text);
}
