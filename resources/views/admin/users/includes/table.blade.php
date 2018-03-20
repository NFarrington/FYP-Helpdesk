@if($users->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-hover {{ $users->hasPages() ? 'table-bordered-bottom' : '' }}">
            <tr>
                <th>{{ __('user.key.id') }}</th>
                <th>{{ __('user.key.name') }}</th>
                <th>{{ __('user.key.email') }}</th>
                <th>{{ __('user.key.email_verified') }}</th>
                <th>{{ __('user.key.roles') }}</th>
                <th>{{ __('user.key.departments') }}</th>
                <th></th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>#{{ $user->id }}</td>
                    <td class="wrap">{{ $user->name }}</td>
                    <td class="wrap">{{ $user->email }}</td>
                    <td>
                        <span class="glyphicon glyphicon-{{ $user->email_verified ? 'ok-sign' : 'remove-sign' }}"></span>
                    </td>
                    <td>
                        <ul class="list-unstyled">
                            @foreach($user->roles as $role)
                                <li>
                                    <a href="{{ route('admin.roles.edit', $role) }}">{{ $role->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul class="list-unstyled">
                            @foreach($user->departments as $department)
                                <li>
                                    <a href="{{ route('admin.departments.edit', $department) }}">{{ $department->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td><a href="{{ route('admin.users.edit', $user) }}">Edit</a></td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="text-center">{{ $users->links() }}</div>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif
