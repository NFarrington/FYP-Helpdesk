@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            Roles
            <a class="btn btn-xs btn-primary pull-right" role="button" href="{{ route('admin.roles.create') }}">
                Create
            </a>
        </div>
        @include('admin.roles.includes.table', ['roles' => $roles])
    </div>
@endsection
