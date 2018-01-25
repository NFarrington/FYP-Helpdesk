<?php

namespace App\Notifications\Tickets;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Submitted extends Notification implements ShouldQueue
{
    use Queueable;

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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
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
            ->subject("$appName - New Ticket Submitted")
            ->line("A new ticket has been submitted to the {$this->ticket->department->name} department.")
            ->line("**Submitted by:** {$this->ticket->user->name}")
            ->line("**Subject:** {$this->ticket->summary}")
            ->action('View Ticket', route('agent.tickets.show', $this->ticket));
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
