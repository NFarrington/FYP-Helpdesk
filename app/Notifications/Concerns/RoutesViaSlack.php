<?php

namespace App\Notifications\Concerns;

use App\Models\SlackWebhook;

trait RoutesViaSlack
{
    /**
     * The Slack webhook to send to.
     *
     * @var \App\Models\SlackWebhook
     */
    protected $webhook = null;

    /**
     * Sets the webhook for the notification.
     *
     * @param \App\Models\SlackWebhook $webhook
     */
    public function setSlackWebhook(SlackWebhook $webhook)
    {
        $this->webhook = $webhook;
    }
}
