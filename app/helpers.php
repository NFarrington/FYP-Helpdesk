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

/**
 * @param \Eloquent|null $model1
 * @param \Eloquent|null $model2
 * @return bool
 */
function match($model1, $model2)
{
    if (!$model1 || !$model2) {
        return;
    }

    return $model1->getKey() === $model2->getKey();
}

/**
 * Call the given Closure with the given value then return the value.
 *
 * @param  mixed $condition
 * @param  mixed $value
 * @param  callable $callback
 * @return mixed
 */
function tap_if($condition, $value, $callback)
{
    if ($condition) {
        $callback($value);
    }

    return $value;
}
