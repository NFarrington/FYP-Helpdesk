@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Permissions</div>
        @include('admin.permissions.includes.table', ['permissions' => $permissions])
    </div>
@endsection
