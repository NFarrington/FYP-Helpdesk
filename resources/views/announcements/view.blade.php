@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ $announcement->title }}
            <span class="pull-right">
                        @can('update', $announcement)
                    <a class="btn btn-xs btn-warning" href="{{ route('announcements.edit', $announcement) }}">Edit</a>
                @endcan
                @can('delete', $announcement)
                    <delete-resource route="{{ route('announcements.destroy', $announcement) }}"></delete-resource>
                @endcan
                    </span>
        </div>
        <div class="panel-body">
            {!! markdown(e($announcement->content)) !!}
        </div>
    </div>
@endsection
