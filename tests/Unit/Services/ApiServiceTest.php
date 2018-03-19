<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\ApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\PersonalAccessTokenResult;
use Tests\TestCase;

class ApiServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The service.
     *
     * @var ApiService
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

        $this->service = $this->app->make(ApiService::class);
        Artisan::call('passport:install', ['--no-interaction' => null]);
    }

    public function testTokenCreation()
    {
        $user = factory(User::class)->create();

        $token = $this->service->create(['name' => 'Test Token'], $user);

        $this->assertInstanceOf(PersonalAccessTokenResult::class, $token);
    }

    public function testGettingOwnedTokens()
    {
        /* @var User $user */
        $user = factory(User::class)->create();
        $user->createToken('Test Token');

        $tokens = $this->service->getOwnedBy($user);

        $this->assertArraySubset(
            ['user_id' => $user->id, 'name' => 'Test Token'],
            $tokens->first()->toArray()
        );
    }
}
