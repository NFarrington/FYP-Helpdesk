@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>
                @include('admin.users.includes.table', ['users' => $users])
            </div>
        </div>
    </div>
@endsection
