<?php

namespace App\Notifications;

use App\Notifications\Concerns\Configurable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class LoginSuccessful extends Notification implements ShouldQueue
{
    use Configurable, Queueable;

    /**
     * The notification key.
     *
     * @var string
     */
    public $key = 'user_login_success';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $appName = config('app.name');

        return (new MailMessage)
            ->success()
            ->subject("$appName - Successful Login Attempt")
            ->line('Your account has just been accessed from a new device.');
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->from(config('app.name') ?: 'Helpdesk', ':information_source:')
            ->to($this->webhook->recipient)
            ->content('Your account has just been accessed from a new device.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
