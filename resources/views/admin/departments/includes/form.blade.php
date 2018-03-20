@if($department->exists)
    <div class="form-group">
        <label class="col-md-4 control-label">ID</label>
        <div class="col-md-6">
            <p class="form-control-static">#{{ $department->id }}</p>
        </div>
    </div>
@endif

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    <label for="name" class="col-md-4 control-label">Name</label>

    <div class="col-md-6">
        <input id="name" type="text" class="form-control" name="name"
               value="{{ old('name') ?: $department->name }}" maxlength="50" required autofocus>

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
               value="{{ old('description') ?: $department->description }}" maxlength="250">

        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group">
    <label for="internal" class="col-md-4 control-label">Internal Only</label>
    <div class="col-md-3">
        <select id="internal" name="internal" class="form-control">
            <option value="0"{{ !$department->internal ? ' selected' : '' }}>
                No
            </option>
            <option value="1"{{ $department->internal ? ' selected' : '' }}>
                Yes
            </option>
        </select>
        @if ($errors->has('internal'))
            <span class="help-block">
                <strong>{{ $errors->first('internal') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('users') ? ' has-error' : '' }}">
    <label for="users" class="col-md-4 control-label">Users</label>

    <div class="col-md-6">
        <select multiple id="users" name="users[]" class="form-control">
            @foreach($users as $user)
                <option value="{{ $user->id }}"
                        {{ collect(old('users') ?: $department->users->pluck('id'))->contains($user->id) ? 'selected' : ''}}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>

        @if ($errors->has('users'))
            <span class="help-block">
                <strong>{{ $errors->first('users') }}</strong>
            </span>
        @endif
        <span class="help-block">
            Only users with at least one role will be listed here.
        </span>
    </div>
</div>
