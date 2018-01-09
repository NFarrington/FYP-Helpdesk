@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Roles</div>
                @include('admin.roles.includes.table', ['roles' => $roles])
            </div>
        </div>
    </div>
@endsection
