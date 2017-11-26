@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Article - {{ $article->title }}</div>
                <div class="panel-body">
                    <p>{{ $article->content }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
