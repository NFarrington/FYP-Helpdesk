@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">User Profile - {{ $user->name }}</div>
                <div class="panel-body">
                    @foreach($user->toArray() as $key => $value)
                        <p>{{ __("user.key.$key") }} - {{$value}}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
