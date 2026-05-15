<?php

return [
    'public_key' => env('KORAPAY_PUBLIC_KEY'),
    'secret_key' => env('KORAPAY_SECRET_KEY'),
    'payment_url' => env('KORAPAY_PAYMENT_URL', 'https://api.korapay.com/merchant'),
    'callback_url' => env('KORAPAY_CALLBACK_URL', '/vote/callback'),
];
