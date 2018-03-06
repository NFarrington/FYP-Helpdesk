@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Roles</div>
        @include('admin.roles.includes.table', ['roles' => $roles])
    </div>
@endsection
