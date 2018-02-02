@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Articles</div>
                @include('articles.includes.table', ['articles' => $articles])
            </div>
        </div>
    </div>
@endsection
