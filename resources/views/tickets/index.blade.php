@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Open Tickets</div>
        @include('tickets.includes.table', ['tickets' => $open])
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Closed Tickets</div>
        @include('tickets.includes.table', ['tickets' => $closed])
    </div>
@endsection
