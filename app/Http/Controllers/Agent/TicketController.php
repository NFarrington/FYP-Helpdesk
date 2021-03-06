<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Agent\Controller as AgentController;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
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
            'assigned' => $this->paginate($tickets->get('assigned', collect()), 10, ['pageName' => 'assigned', 'path' => route('agent.tickets.index')]),
            'open' => $this->paginate($tickets->get('open', collect()), 10, ['pageName' => 'open', 'path' => route('agent.tickets.index')]),
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
            'closed' => $this->paginate($closedTickets, 20, ['path' => route('agent.tickets.index.closed')]),
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
            'department' => 'required|exists:departments,id',
            'agent' => 'nullable|exists:users,id',
            'status' => 'required|exists:ticket_statuses,id',
        ]);

        $agent = User::find($request->input('agent'));
        $ticket->agent_id = $agent && $agent->hasDepartment($request->input('department'))
            ? $agent->id
            : null;

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
