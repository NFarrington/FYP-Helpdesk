<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the dashboard loads successfully.
     *
     * @return void
     */
    public function testDashboardPageLoads()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $response = $this->get(route('api.home'));

        $response->assertStatus(200);
    }
}
