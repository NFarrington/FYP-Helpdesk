<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
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

        return view('staff.tickets.index')->with([
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

        return view('staff.tickets.index-closed')->with([
            'closed' => $closedTickets,
        ]);
    }
}
