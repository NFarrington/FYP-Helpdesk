@if($permissions->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-hover {{ $permissions->hasPages() ? 'table-bordered-bottom' : '' }}">
            <tr>
                <th>{{ __('permission.key.id') }}</th>
                <th>{{ __('permission.key.key') }}</th>
                <th>{{ __('permission.key.name') }}</th>
                <th>{{ __('permission.key.description') }}</th>
                <th>{{ __('permission.key.roles') }}</th>
                <th></th>
            </tr>
            @foreach($permissions as $permission)
                <tr>
                    <td>#{{ $permission->id }}</td>
                    <td>{{ $permission->key }}</td>
                    <td>{{ $permission->name }}</td>
                    <td class="wrap">{{ $permission->description }}</td>
                    <td>
                        <ul class="list-unstyled">
                            @foreach($permission->roles as $role)
                                <li>
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="text-limit">{{ $role->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td><a href="{{ route('admin.permissions.edit', $permission) }}">Edit</a></td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="text-center">{{ $permissions->links() }}</div>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif
