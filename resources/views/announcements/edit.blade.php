@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Update Announcement</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('announcements.update', $announcement) }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        @include('announcements.includes.form')

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
