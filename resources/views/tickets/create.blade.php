@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Ticket</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('tickets.store') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('summary') ? ' has-error' : '' }}">
                                <label for="summary" class="col-md-4 control-label">Summary</label>

                                <div class="col-md-6">
                                    <input id="summary" type="text" class="form-control" name="summary" value="{{ old('summary') }}" maxlength="250" required autofocus>

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
                                    <textarea id="content" class="form-control" name="content" rows="3" maxlength="5000" required></textarea>

                                    @if ($errors->has('content'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Create Ticket
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
