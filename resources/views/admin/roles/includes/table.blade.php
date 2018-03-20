@if($roles->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-hover {{ $roles->hasPages() ? 'table-bordered-bottom' : '' }}">
            <tr>
                <th>{{ __('role.key.id') }}</th>
                <th>{{ __('role.key.key') }}</th>
                <th>{{ __('role.key.name') }}</th>
                <th>{{ __('role.key.description') }}</th>
                <th>{{ __('role.key.users') }}</th>
                <th>{{ __('role.key.permissions') }}</th>
                <th></th>
                <th></th>
            </tr>
            @foreach($roles as $role)
                <tr>
                    <td>#{{ $role->id }}</td>
                    <td class="wrap">{{ $role->key }}</td>
                    <td class="wrap">{{ $role->name }}</td>
                    <td class="wrap">{{ $role->description }}</td>
                    <td>
                        <ul class="list-unstyled">
                            @foreach($role->users as $user)
                                <li>
                                    <a href="{{ route('admin.users.edit', $user) }}">{{ $user->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul class="list-unstyled">
                            @foreach($role->permissions as $permission)
                                <li>
                                    <a href="{{ route('admin.permissions.edit', $permission) }}">{{ $permission->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td><a href="{{ route('admin.roles.edit', $role) }}">Edit</a></td>
                    <td>
                        @can('delete', $role)
                            <delete-resource link-only route="{{ route('admin.roles.destroy', $role) }}"></delete-resource>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="text-center">{{ $roles->links() }}</div>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif
