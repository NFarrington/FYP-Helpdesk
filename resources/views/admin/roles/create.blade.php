@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Create Role</div>

        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('admin.roles.store') }}">
                {{ csrf_field() }}

                @include('admin.roles.includes.form')

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Create
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
