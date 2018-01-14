@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Permissions</div>
                @include('admin.permissions.includes.table', ['permissions' => $permissions])
            </div>
        </div>
    </div>
@endsection
