<?php
// config/vnpay.php
return [
    'tmn'         => env('VNPAY_TMN'),
    'hash_secret' => env('VNPAY_HASH_SECRET'),
    'url'         => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url'  => env('VNPAY_RETURN_URL'),
    'version'     => env('VNPAY_VERSION', '2.1.0'),
    'command'     => env('VNPAY_COMMAND', 'pay'),
    'curr'        => env('VNPAY_CURR', 'VND'),
    'locale'      => env('VNPAY_LOCALE', 'vn'),
];
