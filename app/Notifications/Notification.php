<?php

namespace App\Notifications;

use App\Notifications\Concerns\SlackRoutable;

abstract class Notification extends \Illuminate\Notifications\Notification
{
    use SlackRoutable;
}
