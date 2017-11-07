<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
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
    }

    /**
     * Test the profile page loads successfully.
     *
     * @return void
     */
    public function testProfilePageLoads()
    {
        $response = $this->actingAs($this->user)->get(route('users.show', $this->user));

        $response->assertStatus(200);
    }
}
