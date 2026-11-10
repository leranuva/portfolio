<?php

return [
    'notify_admin' => env('CONTACT_NOTIFY_ADMIN', false),
    'turnstile' => [
        'enabled' => filled(env('TURNSTILE_SITE_KEY')) || filled(env('TURNSTILE_SECRET_KEY')),
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret' => env('TURNSTILE_SECRET_KEY'),
    ],
];
