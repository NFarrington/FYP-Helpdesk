@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Ticket Details</div>
                <div class="panel-body">
                    <p>ID: {{ $ticket->id }}</p>
                    <p>Summary: {{ $ticket->summary }}</p>
                    <form class="form-horizontal" method="POST" action="{{ route('staff.tickets.update', $ticket) }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                            <label for="department" class="col-md-4 control-label">Department</label>

                            <div class="col-md-6">
                                <select id="department" name="department" class="form-control">
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}"
                                                {{ $department->id == (old('department') ?: $ticket->department_id) ? 'selected' : ''}}>
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

                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-md-4 control-label">Status</label>

                            <div class="col-md-6">
                                <select id="status" name="status" class="form-control">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}"
                                                {{ $status->id == (old('status') ?: $ticket->status_id) ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('status'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-warning">
                                    Update Ticket
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @foreach($ticket->posts->sortByDesc('created_at') as $post)
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>Name: {{ $post->user->name }}</p>
                        <p>Content: {{ $post->content }}</p>
                        @if($post->attachment)
                            <p>Attachment: {{ $post->attachment }} <a href="{{ route('posts.attachment', [$ticket, $post]) }}" class="btn btn-primary">Download</a></p>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="panel panel-default">
                <div class="panel-heading">Add Reply</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('posts.store', $ticket->id) }}" enctype="multipart/form-data">
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
