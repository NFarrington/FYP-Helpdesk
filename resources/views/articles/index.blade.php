@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Articles</div>
                @if($articles->isNotEmpty())
                    <table class="table table-hover">
                        <tr>
                            <th>{{ __('article.key.id') }}</th>
                            <th>{{ __('article.key.title') }}</th>
                            <th>{{ __('article.key.created_at') }}</th>
                            <th>{{ __('article.key.updated_at') }}</th>
                        </tr>
                        @foreach($articles as $article)
                                <td>{{ $article->id }}</td>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->created_at }}</td>
                                <td>{{ $article->updated_at }}</td>
                                <td>
                                    @can('view', $article)
                                        <a class="btn btn-xs btn-primary" href="{{ route('articles.show', $article) }}">View</a>
                                    @endcan
                                    @can('update', $article)
                                        <a class="btn btn-xs btn-warning" href="{{ route('articles.edit', $article) }}">Edit</a>
                                    @endcan
                                    @can('delete', $article)
                                        <delete-resource route="{{ route('articles.destroy', $article) }}"></delete-resource>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <div class="panel-body text-center">
                        <span>Nothing to show.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
