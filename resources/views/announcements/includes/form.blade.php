
<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    <label for="title" class="col-md-4 control-label">Title</label>

    <div class="col-md-6">
        <input id="title" type="text" class="form-control" name="title"
               value="{{ old('title') ?: $announcement->title }}" maxlength="250" required autofocus>

        @if ($errors->has('title'))
            <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
    <label for="content" class="col-md-4 control-label">Content</label>

    <div class="col-md-6">
        <textarea id="content" class="form-control" name="content" rows="3" maxlength="60000"
                  required>{{ old('content') ?: $announcement->content }}</textarea>

        @if ($errors->has('content'))
            <span class="help-block">
                <strong>{{ $errors->first('content') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label for="status" class="col-md-4 control-label">Status</label>

    <div class="col-md-6">
        <select id="status" name="status" class="form-control">
            <option value="{{ \App\Models\Announcement::STATUS_UNPUBLISHED }}"
                    {{ (old('status') ?: $announcement->status) == \App\Models\Announcement::STATUS_UNPUBLISHED ? 'selected' : ''}}>
                Unpublished
            </option>
            <option value="{{ \App\Models\Announcement::STATUS_PUBLISHED }}"
                    {{ (old('status') ?: $announcement->status) == \App\Models\Announcement::STATUS_PUBLISHED ? 'selected' : ''}}>
                Published
            </option>
            <option value="{{ \App\Models\Announcement::STATUS_ACTIVE }}"
                    {{ (old('status') ?: $announcement->status) == \App\Models\Announcement::STATUS_ACTIVE ? 'selected' : ''}}>
                Published + Active
            </option>
        </select>

        @if ($errors->has('status'))
            <span class="help-block">
                <strong>{{ $errors->first('status') }}</strong>
            </span>
        @endif
    </div>
</div>
