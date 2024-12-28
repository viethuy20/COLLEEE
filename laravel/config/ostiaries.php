<?php
$env = env('APP_ENV');
if ($env == 'local') {
    return [
        'USE' => false,
        // APIリクエストURL
        'URL' => 'http://test.com/ostiaries',
        'service_id' => '',
        'access_key' => '',
        // プロキシ
        'PROXY' => false,
        // SSL証明書
        'SSL_VERIFY' => false,];
} elseif ($env == 'development') {
    return [
        'USE' => false,
        // APIリクエストURL
        'URL' => 'https://apps-staging.ostiaries.net/2.0/service',
        'service_id' => '7033e5e7-650f-417b-8025-24b2ccff46d0',
        'access_key' => 'IToiT1TdZoCaCbzbRV7WiRia8BOVT0CUXmYrnPIq',
        // プロキシ
        'PROXY' => true,
        // SSL証明書
        'SSL_VERIFY' => true,];
}
return [
    'USE' => true,
    // APIリクエストURL
    'URL' => 'https://apps.ostiaries.net/2.0/service',
    'service_id' => '769e65fc-1361-4d80-b151-b3a7bd7dd656',
    'access_key' => 'H9R7At0buXSVDpOvL61lKHqBD6ji5FXbgSd6kZtd',
    // プロキシ
    'PROXY' => true,
    // SSL証明書
    'SSL_VERIFY' => true,];
