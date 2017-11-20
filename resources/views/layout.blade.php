<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                    <li><a href="{{ route('home') }}">Dashboard</a></li>
                    <li><a href="{{ route('users.show', Auth::user()) }}">Profile</a></li>

                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endguest
            </ul>
            <form class="navbar-form navbar-right">
                <input type="text" class="form-control" placeholder="Search...">
            </form>
        </div>
    </div>
</nav>

<div id="app" class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            @guest
                <ul class="nav nav-sidebar">
                    <nav-sidebar-item name="Login" route="{{ route('login') }}"></nav-sidebar-item>
                    <nav-sidebar-item name="Register" route="{{ route('register') }}"></nav-sidebar-item>
                </ul>
            @else
                <ul class="nav nav-sidebar">
                    <nav-sidebar-item name="Overview" route="{{ route('home') }}"></nav-sidebar-item>
                </ul>

                <ul class="nav nav-sidebar">
                    <nav-sidebar-item name="Create Ticket" route="{{ route('tickets.create') }}"></nav-sidebar-item>
                    <nav-sidebar-item name="My Tickets" route="{{ route('tickets.index') }}"></nav-sidebar-item>
                </ul>

                <ul class="nav nav-sidebar">
                    <nav-sidebar-item name="Create Knowledgebase Article" route="{{ route('articles.create') }}"></nav-sidebar-item>
                    <nav-sidebar-item name="View Knowledgebase Articles" route="{{ route('articles.index') }}"></nav-sidebar-item>
                </ul>
            @endguest
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')

        </div>
    </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
