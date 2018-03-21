@extends('layout-single')

@push('meta')
    <meta name="google" value="notranslate">
@endpush

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            API Keys
            <a class="btn btn-xs btn-primary pull-right" role="button" href="{{ route('profile.api.create') }}">
                Create
            </a>
        </div>
        @if(Session::has('newToken'))
            <div class="panel-body">
                <p>Your new API key has been created. Please make a copy, as you will not be able to access it
                    again.</p>
                <pre style="white-space: pre-wrap;">{{ Session::get('newToken') }}</pre>
            </div>
        @endif

        @include('user-api.includes.table', ['keys' => $keys])
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Documentation</div>
        <div class="panel-body">
            @include('user-api.includes.documentation')
        </div>
    </div>
@endsection
