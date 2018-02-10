@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Announcements</div>
                @include('announcements.includes.table', ['announcements' => $announcements])
            </div>
        </div>
    </div>
@endsection
