@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Update Role</div>

        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('admin.roles.update', $role) }}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                @include('admin.roles.includes.form')

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Update Role
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
