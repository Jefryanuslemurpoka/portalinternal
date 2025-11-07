<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Configuration
    |--------------------------------------------------------------------------
    */

    // Enable/Disable WhatsApp notification
    'enabled' => env('WHATSAPP_ENABLED', true),

    // URL Grup WhatsApp Internal
    'group_url' => env('WHATSAPP_GROUP_URL'),

    // WhatsApp Gateway API Token (Fonnte)
    'api_token' => env('WHATSAPP_API_TOKEN'),

    // API URL
    'api_url' => env('WHATSAPP_API_URL', 'https://api.fonnte.com/send'),

    // Timeout untuk request API (dalam detik)
    'timeout' => env('WHATSAPP_TIMEOUT', 30),
];