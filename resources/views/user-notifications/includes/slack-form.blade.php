<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    <label for="name" class="col-md-4 control-label">Display Name</label>

    <div class="col-md-6">
        <input id="name" type="text" class="form-control" name="name"
               value="{{ old('name') }}" maxlength="250" required>

        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('uri') ? ' has-error' : '' }}">
    <label for="uri" class="col-md-4 control-label">Webhook URI</label>

    <div class="col-md-6">
        <input id="uri" type="text" class="form-control" name="uri"
               value="{{ old('uri') }}" maxlength="250" required>

        @if ($errors->has('uri'))
            <span class="help-block">
                <strong>{{ $errors->first('uri') }}</strong>
            </span>
        @endif
        <span class="help-block">
            To obtain a webhook URI, you will need to set up an <a target="_blank"
                    href="https://my.slack.com/services/new/incoming-webhook/">incoming webhook integration</a>.
        </span>
    </div>
</div>

<div class="form-group{{ $errors->has('recipient') ? ' has-error' : '' }}">
    <label for="recipient" class="col-md-4 control-label">Recipient (Target)</label>

    <div class="col-md-6">
        <input id="recipient" type="text" class="form-control" name="recipient"
               value="{{ old('recipient') }}" maxlength="250" required>

        @if ($errors->has('recipient'))
            <span class="help-block">
                <strong>{{ $errors->first('recipient') }}</strong>
            </span>
        @endif
        <span class="help-block">
            Must be in the form <code>#channel-name</code> or <code>@username</code>.
        </span>
    </div>
</div>
