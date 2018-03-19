<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiTokensTest extends TestCase
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
        $this->actingAs($this->user);
        Artisan::call('passport:install', ['--no-interaction' => null]);
    }

    public function testUserCanListTokens()
    {
        $this->user->createToken('Test Token');
        $response = $this->get(route('profile.api.index'));

        $response->assertSeeText('Test Token');
    }

    public function testTokenCreationPageLoads()
    {
        $response = $this->get(route('profile.api.create'));

        $response->assertSuccessful();
    }

    public function testUserCanCreateTokens()
    {
        $this->get(route('profile.api.create'));
        $response = $this->post(route('profile.api.store'), [
            'name' => 'Test Token',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $response->assertSessionHas('newToken');
    }

    public function testUserCanDeleteTokens()
    {
        $token = $this->user->createToken('Test Token');
        $this->get(route('profile.api.index'));
        $response = $this->delete(route('profile.api.destroy', $token->token->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('oauth_access_tokens', ['name' => 'Test Token']);
    }
}
