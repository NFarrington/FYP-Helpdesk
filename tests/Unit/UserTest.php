<?php

namespace Tests\Unit;

use App\Models\EmailVerification;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

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
    }

    /**
     * Test a user is associated with its email verification.
     *
     * @return void
     */
    public function testUserHasEmailVerification()
    {
        $verification = factory(EmailVerification::class)->make();
        $this->user->emailVerification()->save($verification);

        $this->assertEquals($verification->id, $this->user->emailVerification->id);
    }

    /**
     * Test a user has its roles.
     *
     * @return void
     */
    public function testUserHasRoles()
    {
        $role = factory(Role::class)->create();
        DB::table('role_user')->insert([
            'role_id' => $role->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertEquals($role->id, $this->user->roles()->first()->id);
    }

    /**
     * Test a user is linked to the tickets they have submitted.
     *
     * @return void
     */
    public function testUserHasTickets()
    {
        $ticket = factory(Ticket::class)->create(['user_id' => $this->user->id]);

        $this->assertEquals($ticket->id, $this->user->tickets->first()->id);
    }

    /**
     * Test a user has permissions.
     *
     * @return void
     */
    public function testUserHasPermissions()
    {
        $role = Role::where('name', 'Administrator')->get();
        $this->user->roles()->attach($role);

        $this->assertTrue($this->user->hasPermission(Permission::first()));

        $this->expectException(ModelNotFoundException::class);
        $this->user->hasPermission(str_random());
    }
}
