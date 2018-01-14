
<div class="form-group">
    <label class="col-md-4 control-label">ID</label>
    <div class="col-md-6">
        <p class="form-control-static">#{{ $user->id }}</p>
    </div>
</div>

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    <label for="name" class="col-md-4 control-label">Name</label>

    <div class="col-md-6">
        <input id="name" type="text" class="form-control" name="name"
               value="{{ old('name') ?: $user->name }}" required autofocus>

        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
    <label for="email" class="col-md-4 control-label">E-mail Address</label>

    <div class="col-md-6">
        <input id="email" type="email" class="form-control" name="email"
               value="{{ old('email') ?: $user->email }}" required>

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
        <input id="password" type="password" class="form-control" name="password">

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
    <label for="roles" class="col-md-4 control-label">Roles</label>

    <div class="col-md-6">
        <select multiple id="roles" name="roles[]" class="form-control">
            @foreach($roles as $role)
                <option value="{{ $role->id }}"
                        {{ collect(old('roles') ?: $user->roles->pluck('id'))->contains($role->id) ? 'selected' : ''}}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        @if ($errors->has('roles'))
            <span class="help-block">
                <strong>{{ $errors->first('roles') }}</strong>
            </span>
        @endif
    </div>
</div>
