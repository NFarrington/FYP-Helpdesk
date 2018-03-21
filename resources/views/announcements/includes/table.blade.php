@if($announcements->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>{{ __('models/announcement.key.title') }}</th>
                <th>{{ __('models/announcement.key.created_at') }}</th>
                <th>{{ __('models/announcement.key.updated_at') }}</th>
                <th></th>
            </tr>
            @foreach($announcements as $announcement)
                <tr>
                    <td class="wrap">{{ $announcement->title }}</td>
                    <td>{{ $announcement->created_at }}</td>
                    <td>{{ $announcement->updated_at }}</td>
                    <td><a href="{{ route('announcements.show', $announcement) }}">View</a></td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="text-center">{{ $announcements->links() }}</div>
@else
    <div class="panel-body text-center">
        <span>Nothing to show.</span>
    </div>
@endif
