@extends('layout-single')

@section('content')
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
@endsection
