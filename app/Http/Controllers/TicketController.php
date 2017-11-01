<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Ticket::class);

        return view('tickets.create');
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
            'summary' => 'required|string|max:250',
            'description' => 'required|string|max:5000',
        ]);

        /** @var User $user */
        $user = $request->user();
        $ticket = $user->tickets()->create([
            'summary' => $request->input('summary'),
        ]);

        /** @var TicketPost $ticketPost */
        $ticketPost = TicketPost::make([
            'content' => $request->input('description'),
        ]);

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
     * @codeCoverageIgnore
     * @todo    implement
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket   $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
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
