<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\SetAdminCommand;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The command.
     *
     * @var \App\Console\Commands\SetAdminCommand
     */
    protected $command;

    /**
     * A basic test user.
     *
     * @var \App\Models\User
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

        $this->command = new SetAdminCommand();
        $this->user = factory(User::class)->create();
    }

    public function testCommandSetsUserAsAdmin()
    {
        $this->artisan('user:set-admin', ['user-id' => $this->user->id]);
        $this->assertTrue($this->user->fresh()->can('admin'));
    }

    public function testCommandErrorsWhenUserIsAlreadyAnAdmin()
    {
        $this->user->roles()->attach(Role::admin());
        $exitCode = $this->artisan('user:set-admin', ['user-id' => $this->user->id]);
        $this->assertEquals(1, $exitCode);
    }
}
