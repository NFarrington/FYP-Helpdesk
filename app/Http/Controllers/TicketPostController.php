<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TicketPostController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Ticket $ticket
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $this->validate($request, [
            'reply' => 'required|string|max:5000',
            'attachment' => 'file|nullable|max:10240|mimes:jpeg,bmp,png',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentPath = $attachment->storeAs("attachments/{$ticket->id}", $attachment->getClientOriginalName());
        }

        /** @var TicketPost $ticketPost */
        $ticketPost = TicketPost::make([
            'content' => $request->input('reply'),
            'attachment' => $attachmentPath,
        ]);

        $ticketPost->user()->associate($request->user());
        $ticketPost->ticket()->associate($ticket);
        $ticketPost->save();

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Ticket $ticket
     * @param  \App\Models\TicketPost $post
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

        return redirect()->back()->with('status', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TicketPost $post
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException|\Exception
     */
    public function destroy(Ticket $ticket, TicketPost $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->back()->with('status', 'Post deleted successfully.');
    }

    /**
     * Provides the user with the specified resource's attachment.
     *
     * @param Ticket $ticket
     * @param TicketPost $post
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function viewAttachment(Ticket $ticket, TicketPost $post)
    {
        $this->authorize('view', $ticket);

        if (!$post->attachment) {
            throw new NotFoundHttpException();
        }

        $attachment = Storage::path($post->attachment);

        return response()->download($attachment);
    }
}
