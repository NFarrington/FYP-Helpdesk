
<div class="form-group">
    <label class="col-md-4 control-label">ID</label>
    <div class="col-md-6">
        <p class="form-control-static">#{{ $user->id }}</p>
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label">Name</label>
    <div class="col-md-6">
        <p class="form-control-static">{{ $user->name }}</p>
    </div>
</div>

<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
    <label for="email" class="col-md-4 control-label">E-mail Address</label>

    <div class="col-md-6">
        <input id="email" type="email" class="form-control" name="email"
               value="{{ old('email') ?: $user->email }}" required autofocus>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    <label for="password" class="col-md-4 control-label">Current Password</label>

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
    <label class="col-md-4 control-label">Two-Factor Authentication</label>
    <div class="col-md-6">
        <p class="form-control-static">
            <span class="glyphicon glyphicon-{{ $user->google2fa_secret ? 'ok-sign' : 'remove-sign' }}"></span>
            <a href="{{ route('settings.2fa') }}">Configure</a>
        </p>
    </div>
</div>

<p class="col-md-6 col-md-offset-4 help-block">Leave blank if you do not wish to change your password.</p>
<div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
    <label for="new-password" class="col-md-4 control-label">New Password</label>

    <div class="col-md-6">
        <input id="new-password" type="password" class="form-control" name="new_password">

        @if ($errors->has('new_password'))
            <span class="help-block">
                <strong>{{ $errors->first('new_password') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
    <label for="new-password-confirm" class="col-md-4 control-label">Confirm New Password</label>

    <div class="col-md-6">
        <input id="new-password-confirm" type="password" class="form-control"
               name="new_password_confirmation">

        @if ($errors->has('new_password_confirmation'))
            <span class="help-block">
                <strong>{{ $errors->first('new_password_confirmation') }}</strong>
            </span>
        @endif
    </div>
</div>
