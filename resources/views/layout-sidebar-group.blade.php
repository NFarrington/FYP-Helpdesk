@if($sidebar)
    <ul class="nav nav-sidebar">
        {{ $slot }}
    </ul>
@else
    {{ $slot }}
@endif
