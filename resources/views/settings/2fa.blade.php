@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Two Factor Authentication</div>

        <div class="panel-body">
            <p>To set up two-factor authentication, scan the code below using your authenticator app.</p>
            <p>If you don't have one, <a href="https://support.google.com/accounts/answer/1066447"
                                         target="_blank">click here</a> to set up Google Authenticator.</p>

            <div>
                <img src="{{ $qrCode }}" alt="QR Code" class="img-responsive center-block">
            </div>
            <p>If you are unable to scan the barcode, enter the following code instead:
                <reveal-text hidden="{{ $secret }}"></reveal-text>
            </p>

            <form method="POST" action="{{ route('settings.2fa') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                    <label for="code" class="control-label">Enter the six-digit code from your
                        authenticator</label>

                    <input id="code" type="number" class="form-control" name="code" min="0" max="999999"
                           placeholder="123456" required autofocus>

                    <span class="help-block">
                        @if ($errors->has('code'))
                            <strong>{{ $errors->first('code') }}</strong><br>
                        @endif
                        After scanning the barcode image, the app will display a six-digit code that you can enter above.
                    </span>
                </div>

                <button type="submit" class="btn btn-primary">Enable</button>
            </form>
        </div>
    </div>
@endsection
