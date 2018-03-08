<?php

namespace App\Services;

use App\Models\SlackWebhook;
use App\Models\User;

class NotificationService extends Service
{
    const USER_NOTIFICATIONS = [
        0 => 'user_login_success',
        1 => 'user_login_failed',
        8 => 'user_ticket_with-customer',
        2 => 'user_ticket_closed',
    ];

    const AGENT_NOTIFICATIONS = [
        3 => 'agent_ticket_submitted',
        4 => 'agent_ticket_assigned',
        5 => 'agent_ticket_department-changed',
        7 => 'agent_ticket_with-agent',
        6 => 'agent_ticket_closed',
    ];

    /**
     * Create a new Slack webhook.
     *
     * @param array $attributes
     * @param \App\Models\User $user
     * @return false|\Illuminate\Database\Eloquent\Model
     */
    public function createWebhook(array $attributes, User $user)
    {
        return $user->slackWebhooks()->save(new SlackWebhook($attributes));
    }

    /**
     * Update a user's notification settings.
     *
     * @param \App\Models\User $user
     * @param array $attributes
     * @return mixed
     */
    public function update(User $user, array $attributes)
    {
        foreach($attributes as $key => &$value) {
            if (ends_with($key, '_email')) {
                $value = (boolean) $value;
            } elseif (ends_with($key, '_slack')) {
                $value = (int) $value;
            }

            if (!$value) {
                unset($attributes[$key]);
            }
        }

        return tap($user)->update(['notification_settings' => $attributes]);
    }
}
