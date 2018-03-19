@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            Users
            <a class="btn btn-xs btn-primary pull-right" role="button" href="{{ route('admin.users.create') }}">
                Create
            </a>
        </div>
        @include('admin.users.includes.table', ['users' => $users])
    </div>
@endsection
