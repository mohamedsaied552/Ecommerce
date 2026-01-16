<?php

return [
    'api_key' => env('PAYMOB_API_KEY'),
    'integration_id' => env('PAYMOB_INTEGRATION_ID'),
    'iframe_id' => env('PAYMOB_IFRAME_ID'),
    'hmac' => env('PAYMOB_HMAC'),
    'base_url' => env('PAYMOB_BASE_URL', 'https://accept.paymob.com/api'),
];
