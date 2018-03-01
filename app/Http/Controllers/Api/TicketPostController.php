<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use App\Models\TicketPost;
use Illuminate\Http\Request;

class TicketPostController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Ticket $ticket
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $this->validate($request, [
            'reply' => 'required|string|max:5000',
        ]);

        /** @var TicketPost $post */
        $post = TicketPost::make([
            'content' => $request->input('reply'),
        ]);

        $post->user()->associate($request->user());
        $post->ticket()->associate($ticket);
        $post->save();

        return response()->json($post->attributesToArray(), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Ticket $ticket
     * @param TicketPost $post
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Ticket $ticket, TicketPost $post)
    {
        $this->authorize('update', $post);

        $attributes = $this->validate($request, [
            'content' => 'required|string|max:5000',
        ]);

        $post->fill($attributes);
        $post->save();

        return response()->json($post->attributesToArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket $ticket
     * @param  \App\Models\TicketPost $post
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Ticket $ticket, TicketPost $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(['success' => true]);
    }
}
