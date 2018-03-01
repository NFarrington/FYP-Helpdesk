<div class="col-sm-3 col-md-2 sidebar">
    @guest
        <ul class="nav nav-sidebar">
            <nav-sidebar-item name="Login" route="{{ route('login') }}"></nav-sidebar-item>
            <nav-sidebar-item name="Register" route="{{ route('register') }}"></nav-sidebar-item>
        </ul>
    @else
        <ul class="nav nav-sidebar">
            <nav-sidebar-item name="Overview" route="{{ route('home') }}"></nav-sidebar-item>
        </ul>

        <ul class="nav nav-sidebar">
            <nav-sidebar-item name="Create Ticket" route="{{ route('tickets.create') }}"></nav-sidebar-item>
            <nav-sidebar-item name="My Tickets" route="{{ route('tickets.index') }}"
                              v-bind:active="{{ (int) request()->route()->named('tickets.show') }}"></nav-sidebar-item>
        </ul>

        <ul class="nav nav-sidebar">
            @can('create', \App\Models\Article::class)
                <nav-sidebar-item name="Create Knowledgebase Article" route="{{ route('articles.create') }}"></nav-sidebar-item>
            @endcan
            <nav-sidebar-item name="View Knowledgebase Articles" route="{{ route('articles.index') }}"
                              v-bind:active="{{ (int) request()->route()->named('articles.show', 'articles.edit') }}"></nav-sidebar-item>
        </ul>

        <ul class="nav nav-sidebar">
            @can('create', \App\Models\Announcement::class)
                <nav-sidebar-item name="Create Announcement" route="{{ route('announcements.create') }}"></nav-sidebar-item>
            @endcan
            <nav-sidebar-item name="View Announcements" route="{{ route('announcements.index') }}"
                              v-bind:active="{{ (int) request()->route()->named('announcements.show', 'announcements.edit') }}"></nav-sidebar-item>
        </ul>

        @if(Auth::user()->hasRole(\App\Models\Role::agent()))
            <ul class="nav nav-sidebar">
                <nav-sidebar-item name="View Tickets" route="{{ route('agent.tickets.index') }}"
                                  v-bind:active="{{ (int) request()->route()->named('agent.tickets.show') }}"></nav-sidebar-item>
                <nav-sidebar-item name="Closed Tickets" route="{{ route('agent.tickets.index.closed') }}"></nav-sidebar-item>
            </ul>
        @endif

        @if(Auth::user()->hasRole(\App\Models\Role::admin()))
            <ul class="nav nav-sidebar">
                <nav-sidebar-item name="View Users" route="{{ route('admin.users.index') }}"
                                  v-bind:active="{{ (int) request()->route()->named('admin.users.*') }}"></nav-sidebar-item>
                <nav-sidebar-item name="View Roles" route="{{ route('admin.roles.index') }}"
                                  v-bind:active="{{ (int) request()->route()->named('admin.roles.*') }}"></nav-sidebar-item>
                <nav-sidebar-item name="View Permissions" route="{{ route('admin.permissions.index') }}"
                                  v-bind:active="{{ (int) request()->route()->named('admin.permissions.*') }}"></nav-sidebar-item>
            </ul>
        @endif
    @endguest
</div>
