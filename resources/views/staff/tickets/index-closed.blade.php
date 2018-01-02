@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Closed Tickets</div>
                @include('tickets.includes.ticket-table', ['tickets' => $closed])
            </div>
        </div>
    </div>
@endsection
