
<div class="form-group">
    <label class="col-md-4 control-label">ID</label>
    <div class="col-md-6">
        <p class="form-control-static">#{{ $permission->id }}</p>
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label">Key</label>
    <div class="col-md-6">
        <p class="form-control-static">{{ $permission->key }}</p>
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label">Default</label>
    <div class="col-md-6">
        <p class="form-control-static">
            <span class="glyphicon glyphicon-{{ $permission->default ? 'ok-sign' : 'remove-sign' }}"></span>
        </p>
    </div>
</div>

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    <label for="name" class="col-md-4 control-label">Name</label>

    <div class="col-md-6">
        <input id="name" type="text" class="form-control" name="name"
               value="{{ old('name') ?: $permission->name }}" required autofocus>

        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label for="description" class="col-md-4 control-label">Description</label>

    <div class="col-md-6">
        <input id="description" type="text" class="form-control" name="description"
               value="{{ old('description') ?: $permission->description }}">

        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
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
                        {{ collect(old('roles') ?: $permission->roles->pluck('id'))->contains($role->id) ? 'selected' : ''}}>
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
