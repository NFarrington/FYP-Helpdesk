
@if($tickets->isNotEmpty())
    <table class="table table-hover">
        <tr>
            <th>{{ __('ticket.key.id') }}</th>
            <th>{{ __('ticket.key.user_id') }}</th>
            <th>{{ __('ticket.key.summary') }}</th>
            <th>{{ __('ticket.key.department_id') }}</th>
            <th>{{ __('ticket.key.agent_id') }}</th>
            <th>{{ __('ticket.key.status_id') }}</th>
            <th>{{ __('ticket.key.created_at') }}</th>
            <th>{{ __('ticket.key.updated_at') }}</th>
            <th></th>
        </tr>
        @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->user_id }}</td>
                <td>{{ $ticket->summary }}</td>
                <td>{{ $ticket->department_id }}</td>
                <td>{{ $ticket->agent_id }}</td>
                <td>{{ $ticket->status_id }}</td>
                <td>{{ $ticket->created_at }}</td>
                <td>{{ $ticket->updated_at }}</td>
                <td><a href="{{ route('agent.tickets.show', $ticket) }}">View</a></td>
            </tr>
        @endforeach
    </table>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif
