<?php

namespace Tests\Feature\Staff;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthenticateStaffTest extends TestCase
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
     * Test the ticket index loads successfully.
     *
     * @return void
     */
    public function testAuthenticateStaffMiddleware()
    {
        $this->runBasicRequestWithAllGuards();

        $this->assertFalse(Auth::guard('admin')->check());
        $this->assertFalse(Auth::guard('agent')->check());

        $this->user->roles()->sync([
            Role::where('name', Role::ROLE_ADMIN)->first()->id,
            Role::where('name', Role::ROLE_AGENT)->first()->id,
        ]);
        $this->user = $this->user->fresh();

        $this->runBasicRequestWithAllGuards();

        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertTrue(Auth::guard('agent')->check());
    }

    /**
     * Perform a default request while authenticated through all web guards.
     */
    protected function runBasicRequestWithAllGuards()
    {
        $this->actingAs($this->user, 'admin');
        $this->actingAs($this->user, 'agent');
        $this->actingAs($this->user, 'user');

        $this->get(route('home'));
    }
}
