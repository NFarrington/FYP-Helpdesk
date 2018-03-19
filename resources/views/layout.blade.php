<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="Content-Language" content="{{ app()->getLocale() }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @stack('meta')
</head>
<body>

<div id="app" class="container-fluid">
    @include('layout-navbar')

    <div class="row">

        <div class="col-sm-3 col-md-2 sidebar">
            @include('layout-sidebar', ['sidebar' => true])
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

            @if($announcement = \App\Models\Announcement::active()->first())
                <div class="alert alert-info">
                    <strong>Announcement:</strong>
                    <a href="{{ route('announcements.show', $announcement) }}">{{ $announcement->title }}</a>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </div>
    </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
