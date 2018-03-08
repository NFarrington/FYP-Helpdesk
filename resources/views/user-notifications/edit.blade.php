@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Edit Notifications</div>
        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('profile.notifications.update') }}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                @include('user-notifications.includes.form')

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Add Slack Webhook</div>
        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('profile.notifications.store') }}">
                {{ csrf_field() }}

                @include('user-notifications.includes.slack-form')

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Add
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
