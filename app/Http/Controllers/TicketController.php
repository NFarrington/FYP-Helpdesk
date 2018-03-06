<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;

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
        $this->middleware('auth');

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
        $tickets = $this->service->getTicketsByStatus($request->user());

        return view('tickets.index')->with([
            'open' => $tickets['open'],
            'closed' => $tickets['closed'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request)
    {
        $this->authorize('create', Ticket::class);

        $departments = $this->service->getSubmittableDepartments($request->user());

        return view('tickets.create')->with('departments', $departments);
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
        $this->authorize('create', Ticket::class);

        $attributes = $this->validate($request, [
            'department_id' => 'required|integer|exists:departments,id',
            'summary' => 'required|string|max:250',
            'content' => 'required|string|max:5000',
        ]);

        $ticket = $this->service->create($attributes, $request->user());

        return redirect()->route('tickets.show', $ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket $ticket
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        return view('tickets.view')->with('ticket', $ticket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Ticket $ticket
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $attributes = $this->validate($request, [
            'open' => 'boolean',
            'close' => 'boolean',
        ]);

        $this->service->update($ticket, $attributes);

        return redirect()->route('tickets.index')
            ->with('status', "Ticket {$ticket->id} closed successfully.");
    }
}
