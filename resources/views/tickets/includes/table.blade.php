
@if($tickets->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>{{ __('ticket.key.id') }}</th>
                <th>{{ __('ticket.key.summary') }}</th>
                <th>{{ __('ticket.key.department') }}</th>
                <th>{{ __('ticket.key.status') }}</th>
                <th>{{ __('ticket.key.created_at') }}</th>
                <th>{{ __('ticket.key.updated_at') }}</th>
                <th></th>
            </tr>
            @foreach($tickets as $ticket)
                <tr>
                    <td><a href="{{ route('tickets.show', $ticket) }}">#{{ $ticket->id }}</a></td>
                    <td class="wrap">{{ $ticket->summary }}</td>
                    <td>{{ $ticket->department->name }}</td>
                    <td>{{ $ticket->status->name }}</td>
                    <td>{{ $ticket->created_at }}</td>
                    <td>{{ $ticket->updated_at }}</td>
                    <td><a href="{{ route('tickets.show', $ticket) }}">View</a></td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="text-center">{{ $tickets->links() }}</div>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif
