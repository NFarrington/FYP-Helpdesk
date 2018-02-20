@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Tickets</div>
                @include('tickets.includes.table', ['tickets' => $tickets])
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Articles</div>
                @include('articles.includes.table', ['articles' => $articles])
            </div>

            @if(Auth::user()->hasRole(\App\Models\Role::admin()))
                <div class="panel panel-default">
                    <div class="panel-heading">Users</div>
                    @include('admin.users.includes.table', ['users' => $users])
                </div>
            @endif
        </div>
    </div>
@endsection
