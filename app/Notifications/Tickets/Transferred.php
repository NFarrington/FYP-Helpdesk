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

class Transferred extends Notification implements ShouldQueue
{
    use Configurable, Queueable;

    /**
     * The notification key.
     *
     * @var string
     */
    protected static $key = 'agent_ticket_department-changed';

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
            ->subject("$appName - Ticket Transferred")
            ->line("A ticket has been transferred to the {$this->ticket->department->name} department.")
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
        return parent::toSlack($notifiable)
            ->content("A new ticket has been transferred to the {$this->ticket->department->name} department.")
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
            'old_department' => $this->ticket->getOriginal('department_id'),
            'new_department' => $this->ticket->getAttribute('department_id'),
        ];
    }
}
