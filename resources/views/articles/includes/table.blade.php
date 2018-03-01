
@if($articles->isNotEmpty())
    <table class="table table-hover">
        <tr>
            <th>{{ __('article.key.id') }}</th>
            <th>{{ __('article.key.title') }}</th>
            <th>{{ __('article.key.created_at') }}</th>
            <th>{{ __('article.key.updated_at') }}</th>
        </tr>
        @foreach($articles as $article)
            <tr>
                <td><a href="{{ route('articles.show', $article) }}">#{{ $article->id }}</a></td>
                <td>{{ $article->title }}</td>
                <td>{{ $article->created_at }}</td>
                <td>{{ $article->updated_at }}</td>
            </tr>
        @endforeach
    </table>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif