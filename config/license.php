<?php

return [

    /*
    | Ed25519 public key (base64). The matching private key never ships: it
    | signs licenses offline, or later on the cloud app.
    */
    'public_key' => env('LICENSE_PUBLIC_KEY'),

    /*
    | Vendor-only. Never set this on a customer install: whoever holds it can
    | mint a license for any plan.
    */
    'signing_key' => env('LICENSE_PRIVATE_KEY'),

    /*
    | Where the license is verified and refreshed. Only a hosted customer sets
    | this; a self-hosted install leaves it empty and pastes a token by hand.
    */
    'cloud_url' => env('LICENSE_CLOUD_URL'),

    /*
    | Shared secret a lakasir-cloud install must present to provision a company
    | on this server. Empty disables the endpoint entirely.
    */
    'provision_secret' => env('LICENSE_PROVISION_SECRET'),

    /*
    | How long an expired token keeps working. A shop that cannot reach the
    | network, or whose install is offline for a while, must not stop trading.
    */
    'grace_days' => (int) env('LICENSE_GRACE_DAYS', 30),

    /*
    | Days a hosted company runs before it needs a paid subscription. Only
    | lakasir-cloud provisioning starts a trial; a self-hosted install never
    | does, so it is never charged.
    */
    'trial_days' => (int) env('LICENSE_TRIAL_DAYS', 30),

    /*
    | Limits used when no token is present. null means unlimited, which keeps
    | existing installs working; set numbers here once a free tier exists.
    */
    'free' => [
        'limits' => [
            'users' => null,
            'products' => null,
        ],
        'features' => [],
    ],
];
