<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\SlackWebhook;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationSettingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test user.
     *
     * @var User
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->user->departments()->attach(1);
        $this->user->roles()->attach(Role::agent()->id);
        Role::agent()->permissions()->sync(Permission::pluck('id'));
        $this->actingAs($this->user);
    }

    /**
     * @covers \App\Http\Controllers\UserNotificationsController::__construct()
     * @covers \App\Http\Controllers\UserNotificationsController::show()
     */
    public function testUserCanViewTheirNotificationSettings()
    {
        $response = $this->get(route('profile.notifications.show'));
        $response->assertSuccessful();
    }

    /**
     * @covers \App\Http\Controllers\UserNotificationsController::__construct()
     * @covers \App\Http\Controllers\UserNotificationsController::update()
     */
    public function testUserCanUpdateTheirNotificationSettings()
    {
        $webhook = new SlackWebhook([
            'name' => 'Test Webhook',
            'uri' => 'https://hooks.slack.com/services/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',
            'recipient' => '#general',
        ]);
        $webhook->user_id = $this->user->id;
        $webhook->save();

        $notifications = [];
        foreach (NotificationService::USER_NOTIFICATIONS as $index => $notification) {
            $notifications["{$notification}_email"] = ['0', '1'][mt_rand(0, 1)];
            $notifications["{$notification}_slack"] = ['', "$webhook->id"][mt_rand(0, 1)];
        }
        foreach (NotificationService::AGENT_NOTIFICATIONS as $index => $notification) {
            $notifications["{$notification}_email"] = ['0', '1'][mt_rand(0, 1)];
            $notifications["{$notification}_slack"] = ['', "$webhook->id"][mt_rand(0, 1)];
        }

        $this->get(route('profile.notifications.show'));
        $response = $this->put(route('profile.notifications.update'), $notifications);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    /**
     * @covers \App\Http\Controllers\UserNotificationsController::__construct()
     * @covers \App\Http\Controllers\UserNotificationsController::store()
     */
    public function testUserCanAddNewSlackWebhook()
    {
        $this->get(route('profile.notifications.show'));
        $response = $this->post(route('profile.notifications.store'), [
            'name' => 'Test Webhook',
            'uri' => 'https://hooks.slack.com/services/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',
            'recipient' => '#general',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }
}
