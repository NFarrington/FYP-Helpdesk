@if($role->exists)
    <div class="form-group">
        <label class="col-md-4 control-label">ID</label>
        <div class="col-md-6">
            <p class="form-control-static">#{{ $role->id }}</p>
        </div>
    </div>
@endif

<div class="form-group{{ $errors->has('key') ? ' has-error' : '' }}">
    <label for="key" class="col-md-4 control-label">Key</label>
    <div class="col-md-6">
        @if($role->exists)
            <p class="form-control-static">{{ $role->key }}</p>
        @else
            <input id="key" type="text" class="form-control" name="key"
                   value="{{ old('key') ?: $role->key }}" required autofocus>

            @if ($errors->has('key'))
                <span class="help-block">
                    <strong>{{ $errors->first('key') }}</strong>
                </span>
            @endif
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    <label for="name" class="col-md-4 control-label">Name</label>

    <div class="col-md-6">
        <input id="name" type="text" class="form-control" name="name"
               value="{{ old('name') ?: $role->name }}" required {{ $role->exists ? 'autofocus' : '' }}>

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
               value="{{ old('description') ?: $role->description }}">

        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label">Users</label>
    <div class="col-md-6">
        <p class="form-control-static">You can add roles to individual users in the <a
                    href="{{ route('admin.users.index') }}">users list</a>.</p>
    </div>
</div>

<div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
    <label for="permissions" class="col-md-4 control-label">Permissions</label>

    <div class="col-md-6">
        <select multiple id="permissions" name="permissions[]" class="form-control">
            @foreach($permissions as $permission)
                <option value="{{ $permission->id }}"
                        {{ collect(old('permissions') ?: $role->permissions->pluck('id'))->contains($permission->id) ? 'selected' : ''}}>
                    {{ $permission->name }}
                </option>
            @endforeach
        </select>

        @if ($errors->has('permissions'))
            <span class="help-block">
                <strong>{{ $errors->first('permissions') }}</strong>
            </span>
        @endif
    </div>
</div>
