
@if($tickets->isNotEmpty())
    <table class="table table-hover">
        <tr>
            @foreach($tickets->first()->toArray() as $key => $value)
                <th>{{ __("ticket.key.$key") }}</th>
            @endforeach
        </tr>
        @foreach($tickets as $ticket)
            <tr>
                @foreach($ticket->toArray() as $key => $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
        @endforeach
    </table>
@else
    <p style="text-align: center;">Nothing to show.</p>
@endif
