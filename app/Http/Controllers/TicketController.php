<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Department;
use App\Models\TicketPost;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @codeCoverageIgnore
     * @todo    implement
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $openTickets = Auth::user()->tickets()->open()->get();
        $closedTickets = Auth::user()->tickets()->closed()->get();

        return view('tickets.index')->with([
            'open' => $openTickets,
            'closed' => $closedTickets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Ticket::class);

        $departments = Department::external()->get();

        return view('tickets.create')->with('departments', $departments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Ticket::class);

        $this->validate($request, [
            'department' => 'required|numeric|exists:departments,id',
            'summary' => 'required|string|max:250',
            'content' => 'required|string|max:5000',
        ]);

        $department = Department::find($request->input('department'));
        $this->authorize('submit-ticket', $department);

        /** @var User $user */
        $user = $request->user();

        /** @var Ticket $ticket */
        $ticket = $user->tickets()->make($request->only('summary'));
        $ticket->department()->associate($department);
        $ticket->status()->associate(TicketStatus::withAgent()->orderBy('id')->first());
        $ticket->save();

        /** @var TicketPost $ticketPost */
        $ticketPost = TicketPost::make($request->only('content'));
        $ticketPost->user()->associate($user);
        $ticketPost->ticket()->associate($ticket);
        $ticketPost->save();

        return redirect(route('tickets.show', $ticket->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket   $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        return view('tickets.view')->with('ticket', $ticket);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @codeCoverageIgnore
     * @todo    implement
     *
     * @param  \App\Models\Ticket   $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket   $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        if ($request->input('close') === 'true') {
            $ticket->status()->associate(TicketStatus::closed()->orderBy('id')->first());
            $ticket->save();
        }

        return redirect(route('tickets.index'))->with('status', "Ticket {$ticket->id} closed successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @codeCoverageIgnore
     * @todo    implement
     *
     * @param  \App\Models\Ticket   $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
