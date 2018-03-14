
@if($departments->isNotEmpty())
    <table class="table table-hover {{ $departments->hasPages() ? 'table-bordered-bottom' : '' }}">
        <tr>
            <th>{{ __('department.key.id') }}</th>
            <th>{{ __('department.key.name') }}</th>
            <th>{{ __('department.key.description') }}</th>
            <th>{{ __('department.key.internal') }}</th>
            <th>{{ __('department.key.users') }}</th>
            <th></th>
        </tr>
        @foreach($departments as $department)
            <tr>
                <td>#{{ $department->id }}</td>
                <td>{{ $department->name }}</td>
                <td>{{ $department->description }}</td>
                <td><span class="glyphicon glyphicon-{{ $department->internal ? 'ok-sign' : 'remove-sign' }}"></span></td>
                <td>
                    <ul class="list-unstyled">
                        @foreach($department->users as $user)
                            <li>
                                <a href="{{ route('admin.users.edit', $user) }}">{{ $user->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </td>
                <td><a href="{{ route('admin.departments.edit', $department) }}">Edit</a></td>
            </tr>
        @endforeach
    </table>

    <div class="text-center">{{ $departments->links() }}</div>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif
