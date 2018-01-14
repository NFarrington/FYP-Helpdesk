<?php

namespace App\Http\Controllers\Auth;

use League\OAuth2\Client\Provider\Facebook as FacebookProvider;

/**
 * @link https://developers.facebook.com/docs/facebook-login/manually-build-a-login-flow
 */
class FacebookController extends OAuthController
{
    /**
     * The authorization URL options.
     *
     * @var array
     */
    protected $authUrlOptions = [
        'scope' => ['public_profile', 'email'],
        'auth_type' => 'rerequest',
    ];

    /**
     * The model fields to store the data in.
     *
     * @var array
     */
    protected $dataFields = [
        'id' => 'facebook_id',
        'data' => 'facebook_data',
    ];

    /**
     * Resolves the OAuth provider.
     *
     * @return void
     */
    protected function setUpProvider()
    {
        $this->provider = resolve(FacebookProvider::class);
    }
}
