@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Ticket Details</div>
                <div class="panel-body">
                    <p>ID: {{ $ticket->id }}</p>
                    <p>Summary: {{ $ticket->summary }}</p>
                    <p>Actions:</p>
                    <form class="form-horizontal" method="POST" action="{{ route('tickets.update', $ticket->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <input type="hidden" name="close" value="1">

                        <div class="form-group">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-danger">
                                    Close Ticket
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @foreach($ticket->posts as $post)
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>Name: {{ $post->user->name }}</p>
                        <p>Content: {{ $post->content }}</p>
                        @if($post->attachment)
                            <p>Attachment: {{ $post->attachment }} <a href="{{ route('tickets.posts.attachment', [$ticket, $post]) }}" class="btn btn-primary">Download</a></p>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="panel panel-default">
                <div class="panel-heading">Add Reply</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('tickets.posts.store', $ticket->id) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('reply') ? ' has-error' : '' }}">
                            <label for="reply" class="col-md-3 control-label">Response</label>

                            <div class="col-md-7">
                                <textarea id="reply" class="form-control" name="reply" rows="3" maxlength="5000"
                                          required>{{ old('reply') }}</textarea>

                                @if($errors->has('reply'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('reply') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('attachment') ? ' has-error' : '' }}">
                            <label for="attachment" class="col-md-3 control-label">Attach File</label>
                            <div class="col-md-7">
                                <input type="file" id="attachment" name="attachment" class="form-control"
                                       value="{{ old('attachment') }}">

                                @if($errors->has('attachment'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('attachment') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">
                                    Reply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
