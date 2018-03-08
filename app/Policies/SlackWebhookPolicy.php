<?php

namespace App\Policies;

use App\Models\SlackWebhook;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SlackWebhookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can use the webhook.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\SlackWebhook $slackWebhook
     * @return mixed
     */
    public function use(User $user, SlackWebhook $slackWebhook)
    {
        return $user->id === $slackWebhook->user_id;
    }
}
