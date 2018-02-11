<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The service.
     *
     * @var UserService
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

        $this->service = $this->app->make(UserService::class);
    }

    /**
     * Test the selfUpdate method.
     *
     * @covers \App\Services\UserService::create()
     * @throws \Illuminate\Validation\ValidationException
     */
    public function testSelfUpdate()
    {
        $template = [
            'email' => factory(User::class)->make()->email,
            'password' => 'secret',
            'new_password' => str_random(),
        ];

        $user = factory(User::class)->create();
        $this->service->selfUpdate($user, $template);
        $this->assertDatabaseHas($user->getTable(), array_only($template, 'email'));
    }
}
