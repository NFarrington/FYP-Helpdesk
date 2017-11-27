@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
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
                    <p>{{ $article->content }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
