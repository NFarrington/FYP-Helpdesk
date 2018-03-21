{!! markdown(e(File::get(resource_path('markdown/api/announcements.md')))) !!}

{!! markdown(e(File::get(resource_path('markdown/api/articles.md')))) !!}

{!! markdown(e(File::get(resource_path('markdown/api/tickets.md')))) !!}

{!! markdown(e(File::get(resource_path('markdown/api/ticket-posts.md')))) !!}

@can('admin')
    {!! markdown(e(File::get(resource_path('markdown/api/users.md')))) !!}

    {!! markdown(e(File::get(resource_path('markdown/api/roles.md')))) !!}

    {!! markdown(e(File::get(resource_path('markdown/api/permissions.md')))) !!}
@endcan
