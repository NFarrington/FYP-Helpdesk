<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
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
        $this->user->roles()->attach(Role::admin()->id);
        Passport::actingAs($this->user);
    }

    /**
     * Test the user index loads successfully.
     *
     * @return void
     */
    public function testUserIndexPageLoads()
    {
        $response = $this->get(route('api.users.index'));

        $response->assertStatus(200);
        $response->assertSeeText($this->user->name);
    }

    /**
     * Test the user can be updated successfully.
     *
     * @return void
     */
    public function testUserCanBeViewed()
    {
        $response = $this->get(route('api.users.show', $this->user));

        $response->assertStatus(200);
        $response->assertSeeText($this->user->name);
    }
}
