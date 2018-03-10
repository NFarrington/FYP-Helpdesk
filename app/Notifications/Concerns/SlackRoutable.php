<?php

namespace App\Notifications\Concerns;

use App\Models\SlackWebhook;
use Illuminate\Notifications\Messages\SlackMessage;

trait SlackRoutable
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

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->from(config('app.name') ?: 'Helpdesk', ':information_source:')
            ->to($this->webhook->recipient);
    }
}
