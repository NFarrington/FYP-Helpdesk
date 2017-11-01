<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketPost;
use Illuminate\Http\Request;

class TicketPostController extends Controller
{
    /**
     * Display a listing of the resource.
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Ticket   $ticket
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $this->validate($request, [
            'reply' => 'required|string|max:5000',
        ]);

        /** @var TicketPost $ticketPost */
        $ticketPost = TicketPost::make([
            'content' => $request->input('reply'),
        ]);

        $ticketPost->user()->associate($request->user());
        $ticketPost->ticket()->associate($ticket);
        $ticketPost->save();

        return redirect(route('tickets.show', $ticket));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TicketPost  $ticketPost
     * @return \Illuminate\Http\Response
     */
    public function show(TicketPost $ticketPost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TicketPost  $ticketPost
     * @return \Illuminate\Http\Response
     */
    public function edit(TicketPost $ticketPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TicketPost  $ticketPost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TicketPost $ticketPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TicketPost  $ticketPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketPost $ticketPost)
    {
        //
    }
}
