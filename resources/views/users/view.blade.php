@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">User Profile - {{ $user->name }}</div>
        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('profile.update') }}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                @include('users.includes.form')

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
