<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Webhook URL
    |--------------------------------------------------------------------------
    | Generic webhook URL to POST lead data when a new lead is created.
    | Works with Zapier, Make (Integromat), n8n, or any HTTP endpoint.
    | Leave empty to disable.
    */
    'webhook_url' => env('LEAD_WEBHOOK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Brevo (ex-Sendinblue) Integration
    |--------------------------------------------------------------------------
    | Add leads to Brevo for email automation sequences.
    */
    'brevo' => [
        'enabled' => (bool) env('BREVO_API_KEY'),
        'api_key' => env('BREVO_API_KEY'),
        'list_id' => env('BREVO_LEADS_LIST_ID', 0),
    ],
];
