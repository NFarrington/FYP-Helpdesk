<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Agent\Controller as AgentController;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends AgentController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tickets = Ticket::managedBy($request->user())->open()->get()->groupBy(function ($ticket) {
            if ($ticket->agent !== null && $ticket->agent->id === Auth::user()->id) {
                return 'assigned';
            }

            return 'open';
        });

        return view('agent.tickets.index')->with([
            'assigned' => $tickets->get('assigned') ?: collect(),
            'open' => $tickets->get('open') ?: collect(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function indexClosed(Request $request)
    {
        $closedTickets = Ticket::managedBy($request->user())->closed()->get();

        return view('agent.tickets.index-closed')->with([
            'closed' => $closedTickets,
        ]);
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
        $this->authorize('view-as-agent', $ticket);

        return view('agent.tickets.view')->with([
            'ticket' => $ticket,
            'departments' => Department::all(),
            'statuses' => TicketStatus::all(),
        ]);
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
        $this->authorize('update-as-agent', $ticket);

        $this->validate($request, [
            'department' => 'required|integer|exists:departments,id',
            'status' => 'required|integer|exists:ticket_statuses,id',
        ]);

        $ticket->department_id = $request->input('department');
        $ticket->status_id = $request->input('status');
        $ticket->save();

        $ticket = $ticket->fresh();
        $route = $request->user()->can('view-as-agent', $ticket)
            ? route('agent.tickets.show', $ticket)
            : route('agent.tickets.index');

        return redirect($route)
            ->with('status', "Ticket #{$ticket->id} was updated successfully.");
    }
}
