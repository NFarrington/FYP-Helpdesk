@foreach($notifications as $index => $value)
    <div class="form-group{{ $errors->has($value) ? ' has-error' : '' }}">
        <label for="{{ $value }}_email" class="col-md-4 control-label">{{ __("notification.name.$value") }}</label>

        <div class="col-md-3">
            <select id="{{ $value }}_email" name="{{ $value }}_email" class="form-control">
                <option value="0"{{ !array_get($user->notification_settings, "{$value}_email") ? ' selected' : '' }}>
                    None
                </option>
                <option value="1"{{ array_get($user->notification_settings, "{$value}_email") ? ' selected' : '' }}>
                    {{ $user->email }}
                </option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="{{ $value }}_slack" name="{{ $value }}_slack" class="form-control">
                <option value="" {{ !array_get($user->notification_settings, "{$value}_slack") ? ' selected' : '' }}>
                    None
                </option>
                @foreach($user->slackWebhooks as $webhook)
                    <option value="{{ $webhook->id }}" {{ array_get($user->notification_settings, "{$value}_slack") === $webhook->id ? ' selected' : '' }}>
                        {{ $webhook->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endforeach
