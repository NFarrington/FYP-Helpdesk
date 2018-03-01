<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test dashboard is not accessible by unauthenticated users.
     *
     * @return void
     */
    public function testDashboardRedirectsGuests()
    {
        $response = $this->get(route('home'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test the dashboard loads successfully.
     *
     * @return void
     */
    public function testDashboardPageLoads()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200);
    }
}
