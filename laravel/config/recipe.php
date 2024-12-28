<?php
$env = env('APP_ENV');
if ($env == 'development') {
    return [
        // APIリクエストURL
        'URL' => 'https://dev04.colleee.net/article/wp-content/themes/article/articleApi.php',
        // プロキシ
        'PROXY' => true,
        // SSL証明書
        'SSL_VERIFY' => false,
    ];
}
return [
    // APIリクエストURL
    'URL' => 'https://colleee.net/article/wp-content/themes/article/articleApi.php',
    // プロキシ
    'PROXY' => true,
    // SSL証明書
    'SSL_VERIFY' => false,
];
