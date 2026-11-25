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
    | How long an expired token keeps working. A shop that cannot reach the
    | network, or whose install is offline for a while, must not stop trading.
    */
    'grace_days' => (int) env('LICENSE_GRACE_DAYS', 30),

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
