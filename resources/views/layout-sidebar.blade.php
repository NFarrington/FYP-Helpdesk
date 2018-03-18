@guest
    @component('layout-sidebar-group', ['sidebar' => $sidebar])
        <nav-item name="Login"
                  class="{{ !$sidebar ? 'visible-xs' : '' }}"
                  route="{{ route('login') }}"></nav-item>
        <nav-item name="Register"
                  class="{{ !$sidebar ? 'visible-xs' : '' }}"
                  route="{{ route('register') }}"></nav-item>
    @endcomponent
@else
    @component('layout-sidebar-group', ['sidebar' => $sidebar])
        <nav-item name="Dashboard"
                  class="{{ !$sidebar ? 'visible-xs' : '' }}"
                  route="{{ route('home') }}"></nav-item>
    @endcomponent

    @if($sidebar && (Auth::user()->can('admin') || Auth::user()->can('agent')))
        <ul class="nav nav-sidebar">
            <li class="dropdown-header">User Pages</li>
        </ul>
    @endif

    @component('layout-sidebar-group', ['sidebar' => $sidebar])
        <nav-item name="Create Ticket"
                  class="{{ !$sidebar ? 'visible-xs' : '' }}"
                  route="{{ route('tickets.create') }}"></nav-item>
        <nav-item name="My Tickets"
                  class="{{ !$sidebar ? 'visible-xs' : '' }}"
                  route="{{ route('tickets.index') }}"
                  v-bind:active="{{ (int) request()->route()->named('tickets.show') }}"></nav-item>
    @endcomponent

    @component('layout-sidebar-group', ['sidebar' => $sidebar])
        @can('create', \App\Models\Article::class)
            <nav-item name="Create Knowledgebase Article"
                      class="{{ !$sidebar ? 'visible-xs' : '' }}"
                      route="{{ route('articles.create') }}"></nav-item>
        @endcan
        <nav-item name="View Knowledgebase Articles"
                  class="{{ !$sidebar ? 'visible-xs' : '' }}"
                  route="{{ route('articles.index') }}"
                  v-bind:active="{{ (int) request()->route()->named('articles.show', 'articles.edit') }}"></nav-item>
    @endcomponent

    @component('layout-sidebar-group', ['sidebar' => $sidebar])
        @can('create', \App\Models\Announcement::class)
            <nav-item name="Create Announcement"
                      class="{{ !$sidebar ? 'visible-xs' : '' }}"
                      route="{{ route('announcements.create') }}"></nav-item>
        @endcan
        <nav-item name="View Announcements"
                  class="{{ !$sidebar ? 'visible-xs' : '' }}"
                  route="{{ route('announcements.index') }}"
                  v-bind:active="{{ (int) request()->route()->named('announcements.show', 'announcements.edit') }}"></nav-item>
    @endcomponent

    @can('agent')
        @if($sidebar)
            <ul class="nav nav-sidebar">
                <li class="dropdown-header">Agent Pages</li>
            </ul>
        @endif
        @component('layout-sidebar-group', ['sidebar' => $sidebar])
            <nav-item name="View Tickets"
                      class="{{ !$sidebar ? 'visible-xs' : '' }}"
                      route="{{ route('agent.tickets.index') }}"
                      v-bind:active="{{ (int) request()->route()->named('agent.tickets.show') }}"></nav-item>
            <nav-item name="Closed Tickets"
                      class="{{ !$sidebar ? 'visible-xs' : '' }}"
                      route="{{ route('agent.tickets.index.closed') }}"></nav-item>
        @endcomponent
    @endif

    @can('admin')
        @if($sidebar)
            <ul class="nav nav-sidebar">
                <li class="dropdown-header">Admin Pages</li>
            </ul>
        @endif
        @component('layout-sidebar-group', ['sidebar' => $sidebar])
            <nav-item name="View Users"
                      class="{{ !$sidebar ? 'visible-xs' : '' }}"
                      route="{{ route('admin.users.index') }}"
                      v-bind:active="{{ (int) request()->route()->named('admin.users.*') }}"></nav-item>
            <nav-item name="View Roles"
                      class="{{ !$sidebar ? 'visible-xs' : '' }}"
                      route="{{ route('admin.roles.index') }}"
                      v-bind:active="{{ (int) request()->route()->named('admin.roles.*') }}"></nav-item>
            <nav-item name="View Permissions"
                      class="{{ !$sidebar ? 'visible-xs' : '' }}"
                      route="{{ route('admin.permissions.index') }}"
                      v-bind:active="{{ (int) request()->route()->named('admin.permissions.*') }}"></nav-item>
            <nav-item name="View Departments"
                      class="{{ !$sidebar ? 'visible-xs' : '' }}"
                      route="{{ route('admin.departments.index') }}"
                      v-bind:active="{{ (int) request()->route()->named('admin.departments.*') }}"></nav-item>
        @endcomponent
    @endif
@endguest
