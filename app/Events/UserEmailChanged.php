<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEmailChanged
{
    use Dispatchable, SerializesModels;

    /**
     * The user that has been saved.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
