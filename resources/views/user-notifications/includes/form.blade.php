<h5>User Notifications</h5>

<div class="form-group">
    <label class="col-md-offset-4 col-md-3">Email</label>
    <label class="col-md-3">Slack</label>
</div>

@include('user-notifications.includes.notification-form', ['notifications' => \App\Services\NotificationService::USER_NOTIFICATIONS])

@if($user->hasRole(\App\Models\Role::agent()))
    <hr>
    <h5>Agent Notifications</h5>
    @include('user-notifications.includes.notification-form', ['notifications' => \App\Services\NotificationService::AGENT_NOTIFICATIONS])
@endif
