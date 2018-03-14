@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Departments</div>
        @include('admin.departments.includes.table', ['departments' => $departments])
    </div>
@endsection
