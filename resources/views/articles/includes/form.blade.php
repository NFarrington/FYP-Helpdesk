
<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    <label for="title" class="col-md-4 control-label">Title</label>

    <div class="col-md-6">
        <input id="title" type="text" class="form-control" name="title"
               value="{{ old('title') ?: $article->title }}" maxlength="250" required autofocus>

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
                  required>{{ old('content') ?: $article->content }}</textarea>

        @if ($errors->has('content'))
            <span class="help-block">
                <strong>{{ $errors->first('content') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('visible_from_date') || $errors->has('visible_from_time') ? ' has-error' : '' }}">
    <label for="visible-from-date" class="col-md-4 control-label">Visible After</label>

    <div class="col-md-3">
        <input id="visible-from-date" type="date" class="form-control" name="visible_from_date"
               value="{{ old('visible_from_date') ?: ($article->visible_from ? $article->visible_from->toDateString() : '') }}" maxlength="250" placeholder="yyyy-mm-dd">
        @if ($errors->has('visible_from_date'))
            <span class="help-block">
                <strong>{{ $errors->first('visible_from_date') }}</strong>
            </span>
        @endif
    </div>
    <div class="col-md-3">
        <input id="visible-from-time" type="time" class="form-control" name="visible_from_time"
               value="{{ old('visible_from_time') ?: ($article->visible_from ? $article->visible_from->format('H:i') : '') }}" maxlength="250" placeholder="hh:mm">
        @if ($errors->has('visible_from_time'))
            <span class="help-block">
                <strong>{{ $errors->first('visible_from_time') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('visible_to_date') || $errors->has('visible_to_time') ? ' has-error' : '' }}">
    <label for="visible-to-date" class="col-md-4 control-label">Visible Until</label>

    <div class="col-md-3">
        <input id="visible-to-date" type="date" class="form-control" name="visible_to_date"
               value="{{ old('visible_to_date') ?: ($article->visible_to ? $article->visible_to->toDateString() : '') }}" maxlength="250" placeholder="yyyy-mm-dd">
        @if ($errors->has('visible_to_date'))
            <span class="help-block">
                <strong>{{ $errors->first('visible_to_date') }}</strong>
            </span>
        @endif
    </div>
    <div class="col-md-3">
        <input id="visible-to-time" type="time" class="form-control" name="visible_to_time"
               value="{{ old('visible_to_time') ?: ($article->visible_to ? $article->visible_to->format('H:i') : '') }}" maxlength="250" placeholder="hh:mm">
        @if ($errors->has('visible_to_time'))
            <span class="help-block">
                <strong>{{ $errors->first('visible_to_time') }}</strong>
            </span>
        @endif
    </div>
</div>
