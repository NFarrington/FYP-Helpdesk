<?php

namespace App\Http\Controllers\Auth;

use League\OAuth2\Client\Provider\Google as GoogleProvider;

/**
 * @link https://developers.google.com/identity/protocols/OAuth2WebServer
 */
class GoogleController extends OAuthController
{
    /**
     * The model fields to store the data in.
     *
     * @var array
     */
    protected $dataFields = [
        'id' => 'google_id',
        'data' => 'google_data',
    ];

    /**
     * Resolves the OAuth provider.
     *
     * @return void
     */
    protected function setUpProvider()
    {
        $this->provider = resolve(GoogleProvider::class);
    }
}
