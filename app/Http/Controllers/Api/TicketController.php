<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class TicketController extends Controller
{
    /**
     * The service.
     *
     * @var TicketService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param TicketService $service
     */
    public function __construct(TicketService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tickets = $this->service->getViewableBy($request->user());

        return Resource::collection($tickets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $attributes = $this->validate($request, [
            'department_id' => 'required|integer|exists:departments,id',
            'summary' => 'required|string|max:250',
            'content' => 'required|string|max:5000',
        ]);

        $ticket = $this->service->create($attributes, $request->user());

        return response()->json($ticket->attributesToArray());
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  \App\Models\Ticket $ticket
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function show(Request $request, Ticket $ticket)
    {
        if (!$request->user()->can('view', $ticket) && !$request->user()->can('viewAsAgent', $ticket)) {
            throw new AuthorizationException(); // @codeCoverageIgnore
        }

        return response()->json($ticket->attributesToArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Ticket $ticket
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $attributes = $this->validate($request, [
            'close' => 'required|boolean',
        ]);

        $this->service->close($ticket, $attributes);

        return response()->json($ticket->attributesToArray());
    }
}
