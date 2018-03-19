@if($keys->isNotEmpty())
    <table class="table table-hover {{ $keys->hasPages() ? 'table-bordered-bottom' : '' }}">
        <tr>
            <th>{{ __('token.key.name') }}</th>
            <th>{{ __('token.key.created_at') }}</th>
            <th></th>
        </tr>
        @foreach($keys as $key)
            <tr>
                <td>{{ $key->name }}</td>
                <td>{{ $key->created_at }}</td>
                <td>
                    <delete-resource link-only route="{{ route('profile.api.destroy', $key) }}"></delete-resource>
                </td>
            </tr>
        @endforeach
    </table>

    <div class="text-center">{{ $keys->links() }}</div>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif
