<?php

namespace App\Notifications\Tickets;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Concerns\RoutesViaSlack;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class Assigned extends Notification implements ShouldQueue
{
    use Queueable, RoutesViaSlack;

    /**
     * The notification key.
     *
     * @var string
     */
    public $key = 'agent_ticket_assigned';

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
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
            ->subject("$appName - Ticket Assigned")
            ->line('The following ticket has been assigned to you.')
            ->line("**Submitted by:** {$this->ticket->user->name}")
            ->line("**Subject:** {$this->ticket->summary}")
            ->action('View Ticket', route('agent.tickets.show', $this->ticket));
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
            ->content(sprintf(
                'The following ticket has been assigned to %s.', $this->ticket->agent->name
            ))
            ->attachment(function ($attachment) {
                /* @var \Illuminate\Notifications\Messages\SlackAttachment $attachment */
                $attachment->title('Ticket #'.$this->ticket->id, route('agent.tickets.show', $this->ticket))
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
            'agent_id' => $this->ticket->agent_id,
        ];
    }
}
