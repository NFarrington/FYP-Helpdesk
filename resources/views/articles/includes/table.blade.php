@if($articles->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>{{ __('article.key.id') }}</th>
                <th>{{ __('article.key.title') }}</th>
                @can('view', \App\Models\Article::class)
                    <th>{{ __('article.key.status') }}</th>
                @endcan
                <th>{{ __('article.key.created_at') }}</th>
                <th>{{ __('article.key.updated_at') }}</th>
            </tr>
            @foreach($articles as $article)
                <tr>
                    <td><a href="{{ route('articles.show', $article) }}">#{{ $article->id }}</a></td>
                    <td class="wrap">{{ $article->title }}</td>
                    @can('view', \App\Models\Article::class)
                        <td>{{ $article->isPublished() ? 'Published' : 'Unpublished' }}</td>
                    @endcan
                    <td>{{ $article->created_at }}</td>
                    <td>{{ $article->updated_at }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="text-center">{{ $articles->links() }}</div>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif
