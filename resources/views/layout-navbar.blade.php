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
            <form class="navbar-form navbar-right" method="GET" action="{{ route('search') }}">
                <input type="text" class="form-control" name="q" placeholder="Search...">
            </form>
            <ul class="nav navbar-nav navbar-right">
                @guest
                    <nav-item name="Login" route="{{ route('login') }}"></nav-item>
                    <nav-item name="Register" route="{{ route('register') }}"></nav-item>
                @else
                    <nav-item name="Dashboard" class="hidden-xs" route="{{ route('home') }}"></nav-item>
                    @include('layout-sidebar', ['sidebar' => false])
                    <li class="dropdown{{ Request::is('profile*') ? ' active' : '' }}">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Settings <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <nav-item name="Profile" route="{{ route('profile.show') }}"></nav-item>
                            <nav-item name="Notifications" route="{{ route('profile.notifications.show') }}"></nav-item>
                            <nav-item name="API" route="{{ route('profile.api.index') }}"></nav-item>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endguest
            </ul>

        </div>
    </div>
</nav>
