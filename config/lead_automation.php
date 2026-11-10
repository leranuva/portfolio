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

    /*
    |--------------------------------------------------------------------------
    | Follow-up Emails
    |--------------------------------------------------------------------------
    | Send automated follow-up emails at +2, +5, +10 days after lead creation.
    | Set LEAD_FOLLOWUP_EMAILS=false to disable.
    */
    'followup_emails_enabled' => filter_var(env('LEAD_FOLLOWUP_EMAILS', false), FILTER_VALIDATE_BOOLEAN),

    /*
    |--------------------------------------------------------------------------
    | Calendly Integration
    |--------------------------------------------------------------------------
    | Signing key for verifying incoming webhook requests.
    | Webhook URL: POST /webhooks/calendly
    */
    'calendly_webhook_signing_key' => env('CALENDLY_WEBHOOK_SIGNING_KEY'),
];
