@extends('layout-single')

@section('content')
    <div class="panel panel-info">
        <div class="panel-heading">
            #{{ $ticket->id }} - {{ $ticket->summary }}
            <span class="pull-right">
                <a href="{{ route('tickets.update', $ticket) }}" class="btn btn-danger btn-xs"
                   onclick="event.preventDefault(); document.getElementById('close-form').submit();">
                    {{ $ticket->status->isOpen() ? 'Close' : 'Reopen' }}
                </a>
            </span>
            <form id="close-form" action="{{ route('tickets.update', $ticket) }}" method="POST"
                  style="display: none;">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                @if($ticket->status->isOpen())
                    <input type="hidden" name="close" value="1">
                @else
                    <input type="hidden" name="open" value="1">
                @endif
            </form>
        </div>
        <div class="panel-body">
            <ul class="list-inline list-unstyled">
                <li class="col-md-3" style="margin-bottom: 10px;">
                    <label>{{ __('ticket.key.department') }}</label><br>{{ $ticket->department->name }}</li>
                <li class="col-md-3" style="margin-bottom: 10px;">
                    <label>{{ __('ticket.key.status') }}</label><br>{{ $ticket->status->name }}</li>
                <li class="col-md-3" style="margin-bottom: 10px;">
                    <label>{{ __('ticket.key.created_at') }}</label><br>{{ $ticket->created_at }}</li>
                <li class="col-md-3" style="margin-bottom: 10px;">
                    <label>{{ __('ticket.key.updated_at') }}</label><br>{{ $ticket->updated_at }}</li>
            </ul>
        </div>
    </div>
    @foreach($ticket->posts as $post)
        <div class="panel panel-default">
            <div class="panel-heading">{{ $post->user->name }} at {{ $post->created_at }}</div>
            <div class="panel-body">
                {!! nl2br(e($post->content)) !!}
            </div>
            @if($post->attachment)
                <div class="panel-footer small">
                    Attachment: <a
                            href="{{ route('tickets.posts.attachment', [$ticket, $post]) }}">{{ $post->attachment }}</a>
                </div>
            @endif
        </div>
    @endforeach
    <div class="panel panel-default">
        <div class="panel-heading">Add Reply</div>
        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('tickets.posts.store', $ticket->id) }}"
                  enctype="multipart/form-data">
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
@endsection
