@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Users</div>
        @include('admin.users.includes.table', ['users' => $users])
    </div>
@endsection
