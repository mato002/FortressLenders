<?php

return [
    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
        'from_email' => env('MAIL_FROM_ADDRESS', 'noreply@fortresslenders.com'),
        'from_name' => env('MAIL_FROM_NAME', 'Fortress Lenders'),
        'enabled' => env('SENDGRID_ENABLED', false),
    ],

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'phone_number' => env('TWILIO_PHONE_NUMBER'),
        'enabled' => env('TWILIO_ENABLED', false),
    ],

    'stripe' => [
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'enabled' => env('STRIPE_ENABLED', false),
    ],

    'background_check' => [
        'provider' => env('BACKGROUND_CHECK_PROVIDER', 'goodhire'), // goodhire, checkr, etc
        'api_key' => env('BACKGROUND_CHECK_API_KEY'),
        'enabled' => env('BACKGROUND_CHECK_ENABLED', false),
    ],

    'video_interview' => [
        'provider' => env('VIDEO_INTERVIEW_PROVIDER', 'brighthire'), // brighthire, canvas, etc
        'api_key' => env('VIDEO_INTERVIEW_API_KEY'),
        'enabled' => env('VIDEO_INTERVIEW_ENABLED', false),
    ],

    'google_analytics' => [
        'ga4_measurement_id' => env('GA4_MEASUREMENT_ID'),
        'enabled' => env('GA4_ENABLED', false),
    ],
];
