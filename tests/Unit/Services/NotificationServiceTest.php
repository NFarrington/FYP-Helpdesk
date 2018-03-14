<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The service.
     *
     * @var \App\Services\NotificationService
     */
    protected $service;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->service = $this->app->make(NotificationService::class);
    }

    /** @covers \App\Services\NotificationService::createWebhook() */
    public function testCreatesWebhook()
    {
        $webhookData = [
            'name' => 'Test Webhook',
            'uri' => 'https://hooks.slack.com/services/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',
            'recipient' => '#general',
        ];

        $user = factory(User::class)->create();
        $webhook = $this->service->createWebhook([
            'name' => 'Test Webhook',
            'uri' => 'https://hooks.slack.com/services/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',
            'recipient' => '#general',
        ], $user);

        $this->assertArraySubset($webhookData, $webhook->attributesToArray());
    }

    /** @covers \App\Services\NotificationService::update() */
    public function testUpdatesUsersNotifications()
    {
        $user = factory(User::class)->create();
        $user = $this->service->update($user, ['test_email' => 1, 'test_slack' => null]);
        $this->assertEquals(['test_email' => true], $user->attributesToArray()['notification_settings']);
    }
}
