@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Update Permission</div>

        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('admin.permissions.update', $permission) }}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                @include('admin.permissions.includes.form')

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Update Permission
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
