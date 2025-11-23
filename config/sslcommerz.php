<?php
return [
    'store_id' => env('SSLCOMMERZ_STORE_ID'),
    'store_passwd' => env('SSLCOMMERZ_STORE_PASSWORD'),
    'is_live' => env('SSLCOMMERZ_IS_LIVE', false),

    // Main Payment Initiation URL
    'api_url' => env('SSLCOMMERZ_IS_LIVE', false)
        ? 'https://securepay.sslcommerz.com/gwprocess/v4/api.php'
        : 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php',

    // Validation Callback URL
    'validation_url' => env('SSLCOMMERZ_IS_LIVE', false)
    ? 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php'
    : 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php', // <— remove ?wsdl

    'shared_secret' => env('SSLCOMMERZ_SHARED_SECRET'),

];
