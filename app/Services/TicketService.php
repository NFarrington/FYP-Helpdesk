<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\TicketStatus;
use App\Models\User;
use App\Repositories\DepartmentRepository;
use App\Repositories\TicketPostRepository;
use App\Repositories\TicketRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TicketService extends Service
{
    use AuthorizesRequests;

    /**
     * The ticket repository.
     *
     * @var TicketRepository
     */
    protected $ticketRepo;

    /**
     * The ticket post repository.
     *
     * @var TicketPostRepository
     */
    protected $ticketPostRepo;

    /**
     * The department repository.
     *
     * @var DepartmentRepository
     */
    protected $departmentRepo;

    /**
     * Initialise the service.
     *
     * @param TicketRepository $ticketRepo
     * @param TicketPostRepository $ticketPostRepo
     * @param DepartmentRepository $departmentRepo
     */
    public function __construct(
        TicketRepository $ticketRepo,
        TicketPostRepository $ticketPostRepo,
        DepartmentRepository $departmentRepo
    ) {
        $this->ticketRepo = $ticketRepo;
        $this->ticketPostRepo = $ticketPostRepo;
        $this->departmentRepo = $departmentRepo;
    }

    /**
     * Create a new ticket.
     *
     * @param array $attributes
     * @param User $user
     * @return Ticket
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(array $attributes, User $user)
    {
        $department = $this->departmentRepo->getById($attributes['department_id']);
        $this->authorizeForUser($user, 'submit-ticket', $department);

        $ticket = $user->tickets()->make(array_only($attributes, 'summary')); /* @var Ticket $ticket */
        $ticket->department()->associate($department);
        $ticket->status()->associate(TicketStatus::withAgent()->orderBy('id')->first());
        $ticket->save();

        $ticketPost = TicketPost::make(array_only($attributes, 'content')); /* @var TicketPost $ticketPost */
        $ticketPost->user()->associate($user);
        $ticketPost->ticket()->associate($ticket);
        $ticketPost->save();

        return $ticket;
    }

    /**
     * Update the ticket.
     *
     * @param Ticket $ticket
     * @param array $attributes
     */
    public function update(Ticket $ticket, array $attributes)
    {
        if (array_get($attributes, 'open', false)) {
            $ticket->status()->associate(TicketStatus::withAgent()->orderBy('id')->first());
            $ticket->save();
        } elseif (array_get($attributes, 'close', false)) {
            $ticket->status()->associate(TicketStatus::closed()->orderBy('id')->first());
            $ticket->save();
        }
    }

    /**
     * Get all model instances the user can view.
     *
     * @param User $user
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getViewableBy(User $user)
    {
        return Ticket::query()->managedBy($user)->orWhere('user_id', $user->id)->paginate(20);
    }

    /**
     * Get a user's tickets grouped by their status.
     *
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    public function getTicketsByStatus(User $user)
    {
        return collect(['open' => collect(), 'closed' => collect()])->merge(
            $user->tickets->groupBy(function (Ticket $ticket) {
                return $ticket->status->isOpen()
                    ? 'open'
                    : 'closed';
            })
        );
    }

    /**
     * Get the departments the user can submit/transfer to.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]|static[]
     */
    public function getSubmittableDepartments(User $user)
    {
        return $user->hasRole(Role::agent())
            ? $this->departmentRepo->getAll()
            : $this->departmentRepo->getExternal();
    }
}
