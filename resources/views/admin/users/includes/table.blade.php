
@if($users->isNotEmpty())
    <table class="table table-hover {{ $users->hasPages() ? 'table-bordered-bottom' : '' }}">
        <tr>
            <th>{{ __('user.key.id') }}</th>
            <th>{{ __('user.key.name') }}</th>
            <th>{{ __('user.key.email') }}</th>
            <th>{{ __('user.key.email_verified') }}</th>
            <th>{{ __('user.key.roles') }}</th>
            <th>{{ __('user.key.created_at') }}</th>
            <th>{{ __('user.key.updated_at') }}</th>
            <th></th>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="glyphicon glyphicon-{{ $user->email_verified ? 'ok-sign' : 'remove-sign' }}"></span></td>
                <td>
                    <ul class="list-unstyled">
                        @foreach($user->roles as $role)
                            <li>
                                <a href="{{ route('admin.roles.show', $role) }}">{{ $role->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $user->created_at }}</td>
                <td>{{ $user->updated_at }}</td>
                <td><a href="{{ route('admin.users.edit', $user) }}">Edit</a></td>
            </tr>
        @endforeach
    </table>

    <div class="text-center">{{ $users->links() }}</div>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif
