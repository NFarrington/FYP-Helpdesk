@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Login</div>

        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email"
                               value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                Remember Me
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>

                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>
                    </div>
                </div>
            </form>

            <div class="form-horizontal text-center" style="margin-top: 40px;">
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-4">
                        <a href="{{ route('login.facebook') }}" class="btn btn-block btn-social btn-facebook"
                           onclick="event.preventDefault(); document.getElementById('facebook-login-form').submit();">
                            <span class="fa fa-facebook"></span> Sign in with Facebook
                        </a>
                        <form id="facebook-login-form" action="{{ route('login.facebook') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-4">
                        <a href="{{ route('login.google') }}" class="btn btn-block btn-social btn-google"
                           onclick="event.preventDefault(); document.getElementById('google-login-form').submit();">
                            <span class="fa fa-google"></span> Sign in with Google
                        </a>
                        <form id="google-login-form" action="{{ route('login.google') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
