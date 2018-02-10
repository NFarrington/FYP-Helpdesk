
<div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
    <label for="department" class="col-md-4 control-label">Department</label>

    <div class="col-md-6">
        <select id="department" name="department" class="form-control" autofocus>
            @foreach($departments as $department)
                <option value="{{ $department->id }}"
                        {{ old('department') == $department->id ? 'selected' : ''}}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>

        @if ($errors->has('department'))
            <span class="help-block">
                <strong>{{ $errors->first('department') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('summary') ? ' has-error' : '' }}">
    <label for="summary" class="col-md-4 control-label">Summary</label>

    <div class="col-md-6">
        <input id="summary" type="text" class="form-control" name="summary"
               value="{{ old('summary') }}" maxlength="250" required>

        @if ($errors->has('summary'))
            <span class="help-block">
                <strong>{{ $errors->first('summary') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
    <label for="content" class="col-md-4 control-label">Content</label>

    <div class="col-md-6">
        <textarea id="content" class="form-control" name="content" rows="3" maxlength="5000"
                  required>{{ old('content') }}</textarea>

        @if ($errors->has('content'))
            <span class="help-block">
                <strong>{{ $errors->first('content') }}</strong>
            </span>
        @endif
    </div>
</div>
