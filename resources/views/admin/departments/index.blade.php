@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            Departments
            <a class="btn btn-xs btn-primary pull-right" role="button" href="{{ route('admin.departments.create') }}">
                Create
            </a>
        </div>
        @include('admin.departments.includes.table', ['departments' => $departments])
    </div>
@endsection
