<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository extends Repository
{
    /**
     * The order to sort the results by.
     *
     * @var string
     */
    protected $sortOrder = ['updated_at', 'DESC'];

    /**
     * The model.
     *
     * @var Ticket
     */
    protected $model;

    /**
     * Initialise the repository.
     *
     * @param Ticket $model
     */
    public function __construct(Ticket $model)
    {
        $this->model = $model;
    }
}
