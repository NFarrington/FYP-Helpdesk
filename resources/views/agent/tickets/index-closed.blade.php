@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Closed Tickets</div>
        @include('agent.tickets.includes.table', ['tickets' => $closed])
    </div>
@endsection
