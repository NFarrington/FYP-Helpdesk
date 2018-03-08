<?php

namespace App\Notifications\Tickets;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Concerns\Configurable;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class Closed extends Notification implements ShouldQueue
{
    use Configurable, Queueable;

    /**
     * The notification key.
     *
     * @var string
     */
    protected static $key = 'ticket_closed';

    /**
     * The token used to verify the email address.
     *
     * @var Ticket
     */
    protected $ticket;

    /**
     * Create a new notification instance.
     *
     * @param Ticket $ticket
     * @param string $type
     * @return void
     */
    public function __construct(Ticket $ticket, string $type = 'user')
    {
        $this->ticket = $ticket;
        $this->key = "{$type}_{$this->key}";
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

        $route = $notifiable->can('viewAsAgent', $this->ticket)
            ? route('agent.tickets.show', $this->ticket)
            : route('tickets.show', $this->ticket);

        return (new MailMessage)
            ->subject("$appName - Ticket Closed")
            ->line('The following ticket has now been closed.')
            ->line("**Subject:** {$this->ticket->summary}")
            ->action('View Ticket', $route);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $route = $notifiable->can('viewAsAgent', $this->ticket)
            ? route('agent.tickets.show', $this->ticket)
            : route('tickets.show', $this->ticket);

        return (new SlackMessage)
            ->from(config('app.name') ?: 'Helpdesk', ':information_source:')
            ->to($this->webhook->recipient)
            ->content('The following ticket has been closed.')
            ->attachment(function ($attachment) use ($route) {
                /* @var \Illuminate\Notifications\Messages\SlackAttachment $attachment */
                $attachment->title('Ticket #'.$this->ticket->id, $route)
                    ->fields([
                        'Submitted By' => $this->ticket->user->name,
                        'Subject' => $this->ticket->summary,
                    ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  User $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
        ];
    }
}
