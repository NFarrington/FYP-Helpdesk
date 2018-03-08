<?php

namespace App\Notifications\Tickets;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Concerns\RoutesViaSlack;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class WithCustomer extends Notification implements ShouldQueue
{
    use Queueable, RoutesViaSlack;

    /**
     * The token used to verify the email address.
     *
     * @var Ticket
     */
    protected $ticket;

    /**
     * The notification key.
     *
     * @var string
     */
    public $key = 'user_ticket_with-customer';

    /**
     * Create a new notification instance.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $appName = config('app.name');

        return (new MailMessage)
            ->subject("$appName - New Reply")
            ->line("**Subject:** {$this->ticket->summary}")
            ->line("**Response:** {$this->ticket->posts->first()->content}")
            ->action('View Ticket', route('tickets.show', $this->ticket));
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
            ->content('The following ticket has received a new response.')
            ->attachment(function ($attachment) {
                /* @var \Illuminate\Notifications\Messages\SlackAttachment $attachment */
                $attachment->title('Ticket #'.$this->ticket->id, route('tickets.show', $this->ticket))
                    ->fields([
                        'Subject' => $this->ticket->summary,
                        'Response' => $this->ticket->posts->first()->content,
                    ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  User  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
        ];
    }
}
