@extends('layout-single')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Articles</div>
        @include('articles.includes.table', ['articles' => $articles])
    </div>
@endsection
