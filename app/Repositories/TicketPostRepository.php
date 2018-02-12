<?php

namespace App\Repositories;

use App\Models\TicketPost;

class TicketPostRepository extends Repository
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
     * @var TicketPost
     */
    protected $model;

    /**
     * Initialise the repository.
     *
     * @param TicketPost $model
     */
    public function __construct(TicketPost $model)
    {
        $this->model = $model;
    }
}
