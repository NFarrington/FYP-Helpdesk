<?php

namespace Tests\Unit;

use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
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
     * Test a verification is associated with its user.
     *
     * @return void
     */
    public function testVerificationHasUser()
    {
        $verification = factory(EmailVerification::class)->make()->user()->associate($this->user);
        $verification->save();

        $this->assertEquals($this->user->id, $verification->user->id);
    }
}
