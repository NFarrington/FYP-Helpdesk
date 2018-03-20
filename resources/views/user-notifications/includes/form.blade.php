<h5>User Notifications</h5>

<div class="form-group">
    <label class="col-md-offset-4 col-md-3 col-xs-offset-5 col-xs-5">Email</label>
    <label class="col-md-3 col-xs-2">Slack</label>
</div>

@include('user-notifications.includes.notification-form', ['notifications' => \App\Services\NotificationService::USER_NOTIFICATIONS])

@can('agent')
    <hr>
    <h5>Agent Notifications</h5>
    @include('user-notifications.includes.notification-form', ['notifications' => \App\Services\NotificationService::AGENT_NOTIFICATIONS])
@endif
