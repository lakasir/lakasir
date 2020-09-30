<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;

/**
 * Trait SetClietnCredentials
 * @author sheenazien8
 */
trait SetClietnCredentials
{
    protected $oauth_headers = [];

    protected $user;

    protected function setClientCredentialsToken()
    {
        // Create a Client Repo.
        $clientRepo = new ClientRepository();

        // Create a Password Grant Client.
        $passportClient = $clientRepo->createPasswordGrantClient(null, 'Test App '. Str::random(5), url('/'. Str::random(5)));

        // Store Client ID & Client Secret.
        $client_id = $passportClient->id;
        $client_secret = $passportClient->secret;

        // Initiate Guzzle.
        $client = new \GuzzleHttp\Client();

        // Create a Client Credentials Token.
        $oauth = $client->post(url('/oauth/token'), [
            'form_params' => [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'scope' => '',
                'grant_type' => 'client_credentials',
            ],
        ]);

        $user = User::inRandomOrder()->role('owner')->take(1)->first();
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $this->user = $user;

        // Set the Access Token.
        $this->oauth_headers = [
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        // This return is useless. I just do that in case i need the response, sometimes.
        return json_decode($oauth->getBody());
    }
}
