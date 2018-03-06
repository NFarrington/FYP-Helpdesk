@extends('layout-single')

@section('content')
    @if($assigned->isNotEmpty())
        <div class="panel panel-default">
            <div class="panel-heading">Assigned Tickets</div>
            @include('agent.tickets.includes.table', ['tickets' => $assigned])
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">Open Tickets</div>
        @include('agent.tickets.includes.table', ['tickets' => $open])
    </div>
@endsection
