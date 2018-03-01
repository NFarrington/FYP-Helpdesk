@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Two Factor Authentication</div>

        <div class="panel-body">
            <form method="POST" action="{{ route('login.2fa') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                    <label for="code" class="control-label">Authentication Code</label>

                    <input id="code" type="number" class="form-control" name="code" min="0" max="999999"
                           required autofocus>

                    <span class="help-block">
                        @if ($errors->has('code'))
                            <strong>{{ $errors->first('code') }}</strong><br>
                        @endif
                        Open your two-factor authentication app to view your code.
                    </span>
                </div>

                <button type="submit" class="btn btn-primary">Verify</button>
            </form>
        </div>
    </div>
@endsection
