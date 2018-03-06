@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            Article - {{ $article->title }}
            <span class="pull-right">
                @can('update', $article)
                    <a class="btn btn-xs btn-warning" href="{{ route('articles.edit', $article) }}">Edit</a>
                @endcan
                @can('delete', $article)
                    <delete-resource route="{{ route('articles.destroy', $article) }}"></delete-resource>
                @endcan
            </span>
        </div>
        <div class="panel-body">
            {!! markdown(e($article->content)) !!}
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Add Comment/Feedback</div>
        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('articles.comments.store', $article->id) }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                    <label for="content" class="col-md-3 control-label">Comment</label>

                    <div class="col-md-7">
                        <textarea id="content" class="form-control" name="content" rows="3" maxlength="5000"
                                  required>{{ old('content') }}</textarea>

                        @if($errors->has('content'))
                            <span class="help-block">
                                <strong>{{ $errors->first('content') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-7 col-md-offset-3">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach($article->comments as $comment)
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-horizontal">
                    <label class="col-md-3 control-label">Name</label>
                    <div class="col-md-7">
                        <p class="form-control-static">{{ $comment->user->name }}</p>
                    </div>

                    <label class="col-md-3 control-label">Comment</label>
                    <div class="col-md-7">
                        <p class="form-control-static">{{ $comment->content }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
