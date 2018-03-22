@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            Update Department
            <span class="pull-right">
                <delete-resource route="{{ route('admin.departments.destroy', $department) }}"></delete-resource>
            </span>
        </div>

        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('admin.departments.update', $department) }}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                @include('admin.departments.includes.form')

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Update Department
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
