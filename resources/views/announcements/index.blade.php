@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Announcements</div>
        @include('announcements.includes.table', ['announcements' => $announcements])
    </div>
@endsection
